<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.09.15
 * Time: 20:33
 */
require_once(dirname(__FILE__) . '/../../classes/ContactFormClass.php');

class mpm_contactformAjaxFormModuleFrontController extends FrontController
{

  private $_contactFormClass;

  public function initContent()
  {

    if (!$this->ajax) {
      parent::initContent();
    }
  }

  public function displayAjax()
  {
    $this->_contactFormClass = new ContactFormClass();
    $json = array();
    try{
      if (Tools::getValue('action') == 'send'){


        $file_attachment = Tools::fileAttachment('file');


        $name = Tools::getValue('name');

        if(Tools::getValue('phone') && Tools::getValue('phone') != 'undefined'){
          $phone = Tools::getValue('phone');
        }
        else{
          $phone = false;
        }

        if(Tools::getValue('email') && Tools::getValue('email') != 'undefined'){
          $email = Tools::getValue('email');
        }
        else{
          $email = false;
        }

        if(Tools::getValue('subject') && Tools::getValue('subject') != 'undefined'){
          $subject = Tools::getValue('subject');
        }
        else{
          $subject = false;
        }

        $comment = Tools::getValue('comment');

        $result_captcha = Tools::getValue('result_captcha');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $settings = $this->_contactFormClass->getContactForm($id_lang, $id_shop);

        if(isset($settings[0]['id_contactform']) && $settings[0]['id_contactform']){
          $settings = $settings[0];
        }
        else{
          return false;
        }

        if($settings['name_field'] && $settings['name_field_required'] && !$name){
          throw new Exception ( 'name' );
        }
        if(($settings['email_field'] && $settings['email_field_required'] && !Validate::isEmail($email)) || ($settings['email_field'] && $email && !Validate::isEmail($email))){
          throw new Exception ( 'email' );
        }
        if(($settings['phone_field'] && $settings['phone_field_required'] && !$phone) || $settings['phone_field']  && $phone && !ValidateCore::isPhoneNumber($phone)){
          throw new Exception ( 'phone' );
        }
        if($settings['subject_field'] && $settings['subject_field_required'] && !$subject){
          throw new Exception ( 'subject' );
        }
        if(!$comment){
          throw new Exception ( 'comment' );
        }
        if($settings['captcha_field']){
          $captcha_session = Tools::strtolower(Context::getContext()->cookie->_CAPTCHA);
          $captcha = Tools::strtolower($result_captcha);
          if(!$captcha || ($captcha_session !== $captcha)){
            throw new Exception ( 'captcha' );
          }
        }

        $this->setToCustomerService($name, $phone, $email, $subject, $comment, $id_lang, $id_shop, $file_attachment);
        if($settings['email']){
          $emails = explode(',', $settings['email']);
          foreach($emails as $send_to){
            $template_vars = $this->templateMail($name, $phone, $email, $subject, $comment, $file_attachment);
            $template_vars = array('{content}' => $template_vars);
            $send = $this->sendMessage($template_vars, trim($send_to), $email, $file_attachment);
          }

          if( !$send ){
            $json['error'] = 'mess';
          }
          else{
            $json['success'] = 'mess';
          }
        }
      }
      die( json_encode($json) );
    }
    catch(Exception $e){
      $json['error'] = $e->getMessage();
      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }
    die( json_encode($json) );
  }

  public function setToCustomerService($name, $phone, $email, $subject, $comment, $id_lang, $id_shop, $file_attachment)
  {
    $com = ' ';
    $id_contact = 2;
    $contact = new Contact($id_contact, $id_lang);
    $separator = '';

    if($name){
      $separator = ', ';
      $com .= Module::getInstanceByName('mpm_contactform')->l('  Customer name: ').$name.$separator;
    }

    if($email){
      $separator = ', ';
      $com .= Module::getInstanceByName('mpm_contactform')->l('  Customer email: ').$email.$separator;
    }

    if($phone){
      $separator = ', ';
      $com .= Module::getInstanceByName('mpm_contactform')->l('  Phone number: ').$phone.$separator;
    }

    if($subject){
      $separator = ', ';
      $com .= Module::getInstanceByName('mpm_contactform')->l('  Subject Heading: ').$subject.$separator;
    }

    if($comment){
      $separator = '. ';
      $com .= Module::getInstanceByName('mpm_contactform')->l('  Message: ').$comment;
    }

    if($email){
      $id_customer_thread = $this->getIdCustomerThreadByEmail($email, $id_shop);
    }
    else{
      $id_customer_thread = false;
    }

    if($id_customer_thread){
      $old = $this->oldMessage($id_customer_thread, $id_shop);
      if ($old == $com) {
        $contact->email = '';
        $contact->customer_service = 0;
      }
    }

    if ($contact->customer_service) {
      if ((int)$id_customer_thread) {
        $ct = new CustomerThread($id_customer_thread);
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->update();
      }
      else{
        $ct = new CustomerThread();
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->add();
      }

      if ($ct->id) {
        $cm = new CustomerMessage();
        $cm->id_customer_thread = $ct->id;
        $cm->message = $com;
        if (isset($file_attachment['rename']) && !empty($file_attachment['rename']) && rename($file_attachment['tmp_name'], _PS_UPLOAD_DIR_.basename($file_attachment['rename']))) {
          $cm->file_name = $file_attachment['rename'];
          @chmod(_PS_UPLOAD_DIR_.basename($file_attachment['rename']), 0664);
        }
        $cm->ip_address = (int)ip2long(Tools::getRemoteAddr());
        $cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $cm->add();
      }
    }
  }

  public function sendMessage($template_vars, $send_to, $email, $file_attachment){
         $mail = Mail::Send(
            Configuration::get('PS_LANG_DEFAULT'),
            'mpm_contactform',
            Module::getInstanceByName('mpm_contactform')->l('Contact Form'),
            $template_vars,
            "$send_to",
            NULL,
            $email ? $email : NULL,
            NULL,
            $file_attachment,
            NULL,
            dirname(__FILE__).'/../../mails/');
    return $mail;
  }

  public function templateMail($name, $phone, $email, $subject, $comment, $file_attachment){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_contactform/views/templates/hook/templateMail.tpl');
    $baseUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
    $logo = self::$link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO'));
    $data->assign(
      array(
        'logo_url'          => $logo,
        'baseUrl'           => $baseUrl,
        'name'              => $name,
        'phone'             => $phone,
        'email'             => $email,
        'subject'           => $subject,
        'comment'           => $comment,
        'file_attachment'   => $file_attachment['name'],
      )
    );
    return $data->fetch();
  }

  public function getIdCustomerThreadByEmail($email, $id_shop)
  {
    return Db::getInstance()->getValue('
			SELECT cm.id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread cm
			WHERE cm.email = \''.pSQL($email).'\'
				AND cm.id_shop = '.(int)$id_shop
    );
  }

  public function oldMessage($id_customer_thread, $id_shop){
    return Db::getInstance()->getValue('
					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
					LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
					WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$id_shop.'
					ORDER BY cm.date_add DESC');
  }

}