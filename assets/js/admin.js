/* global vabio_params */
(function ($) {
	$(document).ready(function () {
		vabio.init();
	});

	var vabio = {
		addImageButtonWrapper: {},
		frameTitle           : '',
		imageId              : '',

		init: function () {
			vabio.addImageButtonWrapper = $('.vabio-add-images');
			vabio.removeImageWrapper = $('#vabio-remove-image-wrap');
			vabio.addImagePreviewWrapper = $('.vabio-image-preview');
			vabio.imageIdEl = $('#vabio-avatar-image-id');
			vabio.frameTitle = $('.vabio-add-image').data('title');

			vabio.loadAddImage();
			vabio.loadRemoveImage();
		},

		uploadImage: {

			get: function () {
				return wp.media.view.settings.post.vabioUserImageId
			},

			set: function (a) {
				var b = wp.media.view.settings;
				b.post.vabioUserImageId = a.id;
				b.post.vabioUserAvatarSrc = a.url;
				if (b.post.vabioUserImageId && b.post.vabioUserAvatarSrc) {
					vabio.addImagePreviewWrapper.find('img').attr('src', b.post.vabioUserAvatarSrc);
					vabio.removeImageWrapper.show();
					vabio.imageIdEl.val(b.post.vabioUserImageId);
				}

				vabio.uploadImage.frame().close()
			},

			frame: function () {
				if (this._frame) {
					return this._frame
				}
				this._frame = wp.media({
					library : {
						type: 'image'
					},
					multiple: false,
					title   : vabio.frameTitle
				});
				this._frame.on('open', function () {
					var a = $('#vabio-avatar-image-id').val();
					var b = this.state().get('selection');
					var attachment = wp.media.attachment(a);
					attachment.fetch();
					b.add(attachment ? [attachment] : [])
				}, this._frame);
				this._frame.state('library').on('select', this.select);
				return this._frame
			},

			select: function (a) {
				var selectedObject = this.get('selection').single();

				var selection = {
					id: selectedObject ? selectedObject.id : ''
				};

				if (selection.id) {
					selection.url = 0 < selectedObject.sizes ? selectedObject.sizes.medium.url : $('div.attachment-info').find('img').attr('src');
				}

				vabio.uploadImage.set(selection);
			},
		},

		loadAddImage: function () {

			if (typeof (wp) == 'undefined' || typeof (wp.media) == 'undefined') {
				vabio.addImageButtonWrapper.hide();
				return;
			}

			vabio.addImageButtonWrapper.on('click', '.vabio-add-image', function (e) {
				e.preventDefault();
				e.stopPropagation();
				vabio.imageId = '';
				vabio.uploadImage.frame().open()
			});
		},

		loadRemoveImage: function () {

			if (typeof (wp) == 'undefined' || typeof (wp.media) == 'undefined') {
				vabio.removeImageWrapper.hide();
				return;
			}

			vabio.removeImageWrapper.on('click', '#vabio-remove-image', function (e) {
				e.preventDefault();
				e.stopPropagation();
				vabio.imageId = '';
				vabio.addImagePreviewWrapper.find('img').remove();
				vabio.addImagePreviewWrapper.html(vabio_params.defaultImage);
				vabio.imageIdEl.val('');
				vabio.removeImageWrapper.hide();
			});
		},
	}

})(jQuery);
