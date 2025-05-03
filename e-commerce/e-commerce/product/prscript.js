$(document).ready(function() {
  // Add product to cart
  $(".addItemBtn").click(function(e) {
      e.preventDefault();
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var pname = $form.find(".pname").val();
      var pprice = $form.find(".pprice").val();
      var pimage = $form.find(".pimage").val();
      var pcode = $form.find(".pcode").val();
      var pqty = $form.find(".order-qty").val();
      var maxQty = parseInt($form.find(".pstock").val()); // Get the available stock from hidden field

      // Validate the quantity
      if (pqty < 1 || pqty > maxQty) {
          alert("Please enter a valid quantity. Available stock is " + maxQty);
          return;
      }

      // Proceed with AJAX request if quantity is valid
      $.ajax({
          url: 'action.php',
          method: 'post',
          data: {
              pid: pid,
              pname: pname,
              pprice: pprice,
              pqty: pqty,
              pimage: pimage,
              pcode: pcode
          },
          success: function(response) {
              $("#message").html(response);
              window.scrollTo(0, 0);
              load_cart_item_number();
              update_stock_display(pid, pqty);  // Update stock display
          }
      });
  });

  // Load total no. of items added in the cart
  load_cart_item_number();
  
  function load_cart_item_number() {
      $.ajax({
          url: 'action.php',
          method: 'get',
          data: {
              cartItem: "cart_item"
          },
          success: function(response) {
              $("#cart-item").html(response);
          }
      });
  }

  // Function to update the stock quantity on the page
  function update_stock_display(pid, pqty) {
      var stockQtyElement = $("input[type='hidden'][value='" + pid + "']").closest(".form-submit").find(".stock-qty");
      var currentStock = parseInt(stockQtyElement.text());
      stockQtyElement.text(currentStock - pqty);
  }
});
