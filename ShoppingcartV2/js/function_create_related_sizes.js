// Function to create related product size divs  
function createRelatedSizesElement(relatedProducts, relatedProductIdTo, index) {
    var relatedSizesDivs = '';
    var productPriceElement = document.getElementById(`productPrice${index}`);

    // Declare a variable to track whether the first size is selected
    var firstSizeSelected = false;

    // Wrap sizes in a container div
    relatedProducts.forEach((relatedProduct, i) => {
        // checks PRODUCT_FEATURE_TYPE_ID equelsTo SIZE and current PRODUCT_ID_TO equals relatedProductIdTo
        if (relatedProduct.PRODUCT_FEATURE_TYPE_ID === 'SIZE' && relatedProduct.PRODUCT_ID_TO === relatedProductIdTo) {
            var divId = `relatedSizeDiv${index}_${i}`;

            // Check if it's the first size and set it as selected by default
            var isSelected = !firstSizeSelected ? 'border: 1.5px solid red;' : '';

            // Make the size div clickable using onClick function 
            relatedSizesDivs += `<div id="${divId}" class="d-inline-block mr-1 p-1" style="background-color: #e4e6eb; cursor: pointer; ${isSelected}" onclick="handleSizeClick(this, '${relatedProduct.DESCRIPTION}', '${relatedProduct.PRODUCT_IMAGE}', ${relatedProduct.DEFAULT_AMOUNT},'${relatedProduct.PRODUCT_ID_TO}', ${index})"> ${relatedProduct.DESCRIPTION}</div>`;

            // Trigger click event for the first size div
            if (!firstSizeSelected) {
                setTimeout(function () {
                    document.getElementById(divId).click();
                }, 0);
            }

            // Set the related size default_price
            productPriceElement.innerHTML = `Price: ${relatedProduct.DEFAULT_AMOUNT}`;

            // Mark the first size as selected
            firstSizeSelected = true;
        }
    });

    // Close the container div
    return relatedSizesDivs;
}