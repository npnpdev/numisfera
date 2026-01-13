<div style="width:100%;">
    <div style=" width: 633px;
                background-color: #FBFBFB;
                border: 1px solid #dadada;
                 min-height: 520px;
                 margin: 0 auto;
                 -moz-box-shadow: 0 0px 15px #898A8E;
                 -webkit-box-shadow: 0 0px 15px #898A8E;
                 box-shadow: 0 0px 15px #898A8E;">
        <div style="min-height: 45px;"></div>
        <div style="min-height: 100px; text-align: center">
            <a href="{$baseUrl|escape:'htmlall':'UTF-8'}" style="min-height: 100px;
                                        width: 100%">
                <img  src="{$logo_url|escape:'htmlall':'UTF-8'}" style=" ">
            </a>
        </div>
        <div style="margin-top: 25px;
                    min-height: 45px;
                    text-align: center;">
            <span class="title" style=" font-weight:normal;
                                    font-size:22px;
                                    color: #000000;
                                    line-height:25px
                                    ">
                {l s='Contact Form' mod='mpm_contactform'}
            </span><br/>
        </div>
        <div style="margin-top: 15px;
                    width: 590px;
                    min-height: 240px;
                    margin-left: 20px;
                    border-radius: 7px;
                    border: 1px solid #b1b0af;
                    background-color: #fefdfd;
                    ">
            <div style="text-align: center;
                        display: block;
                        color: #000000;
                        font-size: 17px;
                        background-color: #f0f0f0;
                        border-radius: 7px 7px 0 0;
                        padding: 15px;
                        height: 20px;
                        border-bottom: 1px solid #b1b0af;
            ">{l s='Report' mod='mpm_contactform'}</div>
            {if $name}
                <div style="color: #000000;
                            font-size: 17px;
                            border-bottom: 1px solid #b1b0af;
                            width: 590px;
                            min-height: 50px;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            background-color: #fefdfd;
                            border-bottom: 1px solid #b1b0af;
                            border-right: 1px solid #b1b0af;
                            padding: 15px;
                            min-height: 20px;
                            width: 200px;
                            ">{l s='Customer name' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            border-bottom: 1px solid #b1b0af;
                            font-size: 17px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 325px;
                            ">{$name|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
            {if $email}
                <div style="color: #000000;
                            font-size: 17px;
                            width: 590px;
                            min-height: 50px;
                            border-bottom: 1px solid #b1b0af;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            border-bottom: 1px solid #b1b0af;
                            border-right: 1px solid #b1b0af;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 200px;
                            ">{l s='Customer email' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            border-bottom: 1px solid #b1b0af;
                            font-size: 17px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 325px;
                            ">{$email|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
            {if $phone}
                <div style="color: #000000;
                            font-size: 17px;
                            border-bottom: 1px solid #b1b0af;
                            width: 590px;
                            min-height: 50px;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            background-color: #fefdfd;
                            border-bottom: 1px solid #b1b0af;
                            border-right: 1px solid #b1b0af;
                            padding: 15px;
                            min-height: 20px;
                            width: 200px;
                            ">{l s='Phone number' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            border-bottom: 1px solid #b1b0af;
                            font-size: 17px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 325px;
                            ">{$phone|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
            {if $subject}
                <div style="color: #000000;
                            font-size: 17px;
                            width: 590px;
                            min-height: 50px;
                            border-bottom: 1px solid #b1b0af;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            border-bottom: 1px solid #b1b0af;
                            border-right: 1px solid #b1b0af;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 200px;
                            ">{l s='Subject Heading' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            border-bottom: 1px solid #b1b0af;
                            font-size: 17px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 325px;
                            ">{$subject|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
            {if $file_attachment}
                <div style="color: #000000;
                            font-size: 17px;
                            width: 590px;
                            min-height: 50px;
                            border-bottom: 1px solid #b1b0af;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            border-bottom: 1px solid #b1b0af;
                            border-right: 1px solid #b1b0af;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 200px;
                            ">{l s='Attach File' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            border-bottom: 1px solid #b1b0af;
                            font-size: 17px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 20px;
                            width: 325px;
                            ">{$file_attachment|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
            {if $comment}
                <div style="color: #000000;
                            font-size: 17px;
                            min-height: 95px;
                            width: 590px;
                            ">
                    <div style="width: 119px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            border-radius: 0px 0px 0px 7px;
                            border-right: 1px solid #b1b0af;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 65px;
                            width: 200px;
                            ">{l s='Message' mod='mpm_contactform'}</div>
                    <div style="width: 370px;
                            color: #000000;
                            float: left;
                            font-size: 17px;
                            border-radius: 0px 0px 7px 0px;
                            background-color: #fefdfd;
                            padding: 15px;
                            min-height: 65px;
                            width: 325px;
                            ">{$comment|escape:'htmlall':'UTF-8'}</div>
                </div>
            {/if}
        </div>
        <div style="min-height: 55px; text-align: center">---</div>
    </div>
</div>