const CommonPage = require('@versions/177/FO/foBasePage.js');

class Home extends CommonPage {
  constructor() {
    super();

    // Selectors
    this.bannerLink = 'a.banner';
    this.bannerImage = `${this.bannerLink} img`;
  }

  // Functions

  /**
   * Check is banner is displayed
   * @param page
   * @return {Promise<boolean>}
   */
  isBannerVisible(page) {
    return this.elementVisible(page, this.bannerLink, 2000);
  }

  /**
   * Get banner link
   * @param page
   * @return {Promise<string>}
   */
  getBannerLink(page) {
    return this.getAttributeContent(page, this.bannerLink, 'href');
  }

  /**
   * Get banner description
   * @param page
   * @return {Promise<string>}
   */
  getBannerDescription(page) {
    return this.getAttributeContent(page, this.bannerLink, 'title');
  }

  /**
   * Check if banner image is visible
   * @param page
   * @return {Promise<boolean>}
   */
  bannerHasImage(page) {
    return this.elementVisible(page, this.bannerImage, 2000);
  }
}
module.exports = new Home();
