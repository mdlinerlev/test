// валидация форм
var FormValidate = window.FormValidate = function($form, action) {
	this.$form = $form;
	this.action = action;
	this.blockSubmit = true;
	this.bindSubmit();
}
FormValidate.prototype = {
	bindSubmit: function() {
		var that = this;

		this.$form.on('submit', function() {
			if(!that.blockSubmit) return true;

			$.post(
				'/bitrix/templates/totbook/ajax/connector.php',
				that.$form.serialize()+'&act='+that.action,
				function(resp) {
					console.log(resp);
					that.$form.find('[type="submit"]').removeAttr('disabled');
					that.clearErrors();
					if(resp.success) {
						that.blockSubmit = false;
						that.$form.trigger('submit');
					}
					else {

						that.setErrorsToFields(resp);
					}
				},
				'json'
			);
			that.$form.find('[type="submit"]').attr('disabled', true);
			return false;
		});
	},
	setErrorsToFields: function(errors) {
		var fieldName, fieldPos, errorName, arErrorName, $field;
		for(errorName in errors) {
			arErrorName = errorName.split('|');
			fieldName = arErrorName[0];
			fieldPos = arErrorName[1];

			$field = this.$form.find('[name="'+fieldName+'"]').eq(fieldPos);
			$field.addClass('error');

			var $errorWrapper = this.$form.find('.errorWrapper[data-for="'+fieldName+'"]'),
				$errorSpan = $('<span class="formError">'+errors[errorName]+'</span>');
			if( $errorWrapper.length ) {
				$errorWrapper.empty().append($errorSpan);
			}
			else {
				$field.next('.formError').remove();
				$field.after($errorSpan);
			}
		}
	},
	clearErrors: function() {
		this.$form.find('.formError').remove();
		this.$form.find('.error').removeClass('error');
		this.$form.find('.errorWrapper').empty();
	}
}