<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h4>Checkout</h4>
                <form>
                    <!-- Billing Address -->
                    <div class="form-group">
                        <label for="billingAddress">Billing Address</label>
                        <textarea class="form-control" id="billingAddress" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="shippingAddress">Shipping Address</label>
                        <textarea class="form-control" id="shippingAddress" rows="3"></textarea>
                    </div>

                    <!-- Payment Information -->
                    <div class="form-group">
                        <label for="cardNumber">Payment Method</label>
                        <select class="form-control" id="exampleSelect">
                            <option>Pay on delivery</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-dark">Place Order</button>
                </form>
            </div>

            <div class="col-md-4 summary">
                <h5><b>Order Summary</b></h5>
                <hr>
                <div class="row">
                    <div class="col">Items</div>
                    <div class="col text-right">&#8377; 120.00</div>
                </div>
                <div class="row">
                    <div class="col">Shipping</div>
                    <div class="col text-right">&#8377; 40</div>
                </div>
                <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                    <div class="col">Total</div>
                    <div class="col text-right">&#8377; 130.00</div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>