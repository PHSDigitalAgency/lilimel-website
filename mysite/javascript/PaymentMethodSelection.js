;(function($) { 
	$(document).ready(function() { 
		$('<div class="type-credit-card"></div>').hide().insertAfter('.order-form');
		$('.Actions').hide();
		
		$('.order-form').on('change', '.payment-details input', function(e){
			
			if(this.value == "Sips"){
				console.log($('.type-credit-card').html() == '');

				if($('.type-credit-card').html() == ''){
					var orderForm = $('.order-form'),
						ajaxUrl = orderForm.attr('action'),
						data = orderForm.serialize();

					$.ajax({
						url: ajaxUrl,
						type: 'POST',
						data: data,
						beforeSend: function() {
							$('.Actions').hide();
							$('.Actions .loading').show();
						},
						success: function(data){
							$('.type-credit-card').append(data);
						},
						complete: function() {
							$('.type-credit-card').show();
							$('.Actions .loading').hide();
						}
					});

				}else{
					$('.type-credit-card').show();
					$('.Actions').hide();
				}

			}else{
				$('.Actions').show();
				$('.type-credit-card').hide();
			}

		});
	})
})(jQuery);