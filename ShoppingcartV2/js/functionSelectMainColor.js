// Function for select main product color 
function selectColor(mainProduct, index) {
    var mainColorDiv = document.querySelector(`#mainProductColorDiv_${index}`); //get the id from card 
    var productImageElement = document.getElementById(`productImage${index}`);
    var productPriceElement = document.getElementById(`productPrice${index}`);

    //    when user click on parent color than event fires
    mainColorDiv.addEventListener("click", function () {
        var productNameElement = document.getElementById(`productName${index}`);

        // show main product data
        productNameElement.innerHTML = mainProduct.PRODUCT_NAME;// show product name
        productImageElement.src = `img/categories1-img/${mainProduct.PRODUCT_IMAGE}`;
        productPriceElement.innerHTML = `Price: ${mainProduct.PRODUCT_PRICE}`;

        // logs the data fro debugging
        console.log("Product name is:", mainProduct.PRODUCT_NAME);
        console.log("Product image is:", mainProduct.PRODUCT_IMAGE);
        console.log("Product id is:", mainProduct.MAIN_PRODUCT_ID);
        console.log("Selected color is:", mainProduct.DESCRIPTION);
        console.log("Main product  price is :" + mainProduct.PRODUCT_PRICE);
    });
}