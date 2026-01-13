require('module-alias/register');

const {expect} = require('chai');
const browserHelper = require('prestashop_test_lib/kernel/utils/helpers.js');
const configClassMap = require('@utils/configClassMap.js');
const imageHelper = require('@utils/imageHelper.js');
const fileHelper = require('@utils/fileHelper');

// Get resolver
const VersionSelectResolver = require('prestashop_test_lib/kernel/resolvers/versionSelectResolver.js');

const versionSelectResolver = new VersionSelectResolver(global.PS_VERSION, configClassMap);

// Import pages
const loginPage = versionSelectResolver.require('BO/login/index.js');
const dashboardPage = versionSelectResolver.require('BO/dashboard/index.js');
const moduleManagerPage = versionSelectResolver.require('BO/modules/moduleManager/index.js');
const psBannerModulePage = versionSelectResolver.require('BO/modules/ps_banner/index.js');
const homePage = versionSelectResolver.require('FO/home/index.js');

// Browser vars
let browserContext;
let page;

const moduleToInstall = {
  name: 'Banner',
  tag: 'ps_banner',
};

const moduleConfiguration = {
  en: {
    langId: 1,
    imagePath: './image_en.png',
    link: global.FO.URL,
    description: 'This is a description for module ps_banner',
  },
  fr: {
    langId: 2,
    imagePath: './image_fr.png',
    link: global.FO.URL,
    description: 'Ceci est une description du module ps_banner',
  },
};

describe('Go to ps_banner configuration page', async () => {
  // before and after functions
  before(async function () {
    browserContext = await browserHelper.createBrowserContext(this.browser);

    page = await browserHelper.newTab(browserContext);

    // Create images for test
    await Promise.all([
      imageHelper.generateImage(moduleConfiguration.en.imagePath),
      imageHelper.generateImage(moduleConfiguration.fr.imagePath),
    ]);
  });

  after(async () => {
    await browserHelper.closeBrowserContext(browserContext);

    // Delete images after test
    await Promise.all([
      fileHelper.deleteFile(moduleConfiguration.en.imagePath),
      fileHelper.deleteFile(moduleConfiguration.fr.imagePath),
    ]);
  });

  it('should go to login page', async () => {
    await loginPage.goTo(page, global.BO.URL);

    const pageTitle = await loginPage.getPageTitle(page);
    await expect(pageTitle).to.contains(loginPage.pageTitle);
  });

  it('should check PS version', async () => {
    const psVersion = await loginPage.getPrestashopVersion(page);
    await expect(psVersion).to.contains(global.PS_VERSION);
  });

  it('should login into BO with default user', async () => {
    await loginPage.login(page, global.BO.EMAIL, global.BO.PASSWD);
    await dashboardPage.closeOnboardingModal(page);

    const pageTitle = await dashboardPage.getPageTitle(page);
    await expect(pageTitle).to.contains(dashboardPage.pageTitle);
  });

  it('should go to module manager page', async () => {
    await dashboardPage.goToSubMenu(
      page,
      dashboardPage.modulesParentLink,
      dashboardPage.moduleManagerLink,
    );

    const pageTitle = await moduleManagerPage.getPageTitle(page);
    await expect(pageTitle).to.contain(moduleManagerPage.pageTitle);
  });

  it('should check that the module was installed', async () => {
    const isModuleVisible = await moduleManagerPage.searchModule(
      page,
      moduleToInstall.tag,
      moduleToInstall.name,
    );

    await expect(isModuleVisible).to.be.true;
  });

  it('should check that the module is enabled', async () => {
    const isModuleEnabled = await moduleManagerPage.isModuleEnabled(page, moduleToInstall.name);
    await expect(isModuleEnabled).to.be.true;
  });

  it('should go to configuration page', async () => {
    await moduleManagerPage.goToConfigurationPage(page, moduleToInstall.name);

    // Check configuration page
    const pageTitle = await psBannerModulePage.getPageTitle(page);
    await expect(pageTitle).to.contain(psBannerModulePage.pageTitle);

    // Check module name
    const pageSubtitle = await psBannerModulePage.getPageSubtitle(page);
    await expect(pageSubtitle).to.contain(moduleToInstall.name);
  });

  it('should set module configuration', async () => {
    const textResult = await psBannerModulePage.setConfiguration(page, moduleConfiguration);

    await expect(textResult).to.contain(psBannerModulePage.updatedSettingSuccessfulMessage);
  });

  it('should view my shop and check that banner exist', async () => {
    page = await psBannerModulePage.viewMyShop(page);

    const bannerExists = await homePage.isBannerVisible(page);
    await expect(bannerExists).to.be.true;
  });

  it('should check banner link and description in english', async () => {
    await homePage.changeLanguage(page, 'en');

    // Check banner link
    const bannerLink = await homePage.getBannerLink(page);
    await expect(bannerLink).to.equal(moduleConfiguration.en.link);

    // Check banner description
    const bannerDescription = await homePage.getBannerDescription(page);
    await expect(bannerDescription).to.equal(moduleConfiguration.en.description);
  });

  it('should check banner link and description in french', async () => {
    await homePage.changeLanguage(page, 'fr');

    // Check banner link
    const bannerLink = await homePage.getBannerLink(page);
    await expect(bannerLink).to.equal(moduleConfiguration.fr.link);

    // Check banner description
    const bannerDescription = await homePage.getBannerDescription(page);
    await expect(bannerDescription).to.equal(moduleConfiguration.fr.description);
  });
});
