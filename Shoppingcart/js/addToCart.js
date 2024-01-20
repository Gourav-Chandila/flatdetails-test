var cartCountElement = document.getElementById('cartCount');
var cartCount = 0; // Initial cart count
// addToCart function to use the selected size product image and details
function addToCart(index) {
    // Get the selected size information for this product card
    var selectedSize = selectedSizes[index];

    // Create an object representing the selected product
    var selectedProduct = {
        productImage: selectedSize.productImage,
        productName: document.getElementById(`productName${index}`).innerText,
        size: selectedSize.element.innerText,
        price: selectedSize.price,
        quantity: 1,// set quantity by default to 1 
    };

    // Add the selected product to the session
    var selectedProducts = JSON.parse(sessionStorage.selectedProducts || '[]');
    console.log('selected product json ' + selectedProducts);
    selectedProducts.push(selectedProduct);
    sessionStorage.selectedProducts = JSON.stringify(selectedProducts);

    // Update the cart count
    cartCount++;
    // Update the cart count in the UI
    cartCountElement.innerHTML = cartCount;

    // Send an AJAX request to update cartCount and selectedProducts on the server
    $.ajax({
        type: 'POST',
        url: 'updateCartCount.php',
        data: {
            cartCount: cartCount,
            selectedProducts: JSON.stringify(selectedProducts)
        },
        success: function (response) {
            console.log('Cart count and selected products updated on the server.');
        },
        error: function (error) {
            console.error('Error updating cart count and selected products:', error);
        }
    });

    console.log(`Product at card index ${index} added to the cart.`);
}