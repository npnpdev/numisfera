const fs = require('fs');
const imgGen = require('js-image-generator');

module.exports = {
  /**
   * Generate image
   * @param imageName
   * @param width
   * @param height
   * @param quality
   * @return {Promise<void>}
   */
  async generateImage(imageName, width = 200, height = 200, quality = 1) {
    await imgGen.generateImage(width, height, quality, (err, image) => {
      fs.writeFileSync(imageName, image.data);
    });
  },
};
