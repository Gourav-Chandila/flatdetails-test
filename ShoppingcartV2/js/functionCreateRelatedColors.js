// Modify the createSelectableBackgroundElements function to handle related COLOR's 
function createRelatedBackgroundElements(relatedProducts, mainProductId, index) {
    var relatedBackgroundRoundedDivs = '';
    relatedProducts.forEach((relatedProduct, i) => {
        if (relatedProduct.PRODUCT_FEATURE_TYPE_ID === 'COLOR') {
            var divId = `relatedColorDiv${index}_${i}`;
            relatedBackgroundRoundedDivs += `<div id="${divId}" class="rounded-circle d-inline-block mr-1 p-2 border border-secondary related-color" onclick="selectRelatedColor('${relatedProduct.PRODUCT_NAME}','${mainProductId}','${relatedProduct.PRODUCT_IMAGE}', '${index}', '${relatedProduct.PRODUCT_ID_TO}')" style="background-color: ${relatedProduct.DESCRIPTION}; width: 20px; height: 20px;" ></div>`;
        }
    });
    return relatedBackgroundRoundedDivs;
}