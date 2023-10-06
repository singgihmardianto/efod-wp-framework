const cptInputHandler = ($, wp) => {
	var cptInputImageGalleries = () => {
		let imageGalleriesAddBtn = $(".ef-cpt-meta-img-galleries__add_btn");
		let imageGalleriesResult = $(".ef-cpt-meta-img-galleries__list");
		let imageGalleriesInput = $(".ef-cpt-meta-img-galleries__input_hidden");
		let imageGalleriesInputValue =
			imageGalleriesInput.val()?.split(",") ?? "";

		imageGalleriesResult.sortable({
			cursor: "-webkit-grabbing", // mouse cursor
			stop: function (event, ui) {
				ui.item.removeAttr("style");

				let sort = new Array(); // array of image IDs
				const container = $(this);

				// each time after dragging we resort our array
				container.find("li").each(function (index) {
					sort.push($(this).attr("data-id"));
				});
				// add the array value to the hidden input field
				container.parent().next().val(sort.join());
			},
		});

		imageGalleriesAddBtn.click((e) => {
			e.preventDefault();

			const customUploader = wp.media({
				title: "Insert images",
				library: {
					type: "image",
				},
				button: {
					text: "Use these images",
				},
				multiple: true,
			});

			customUploader.on("select", function () {
				// get selected images and rearrange the array
				let selectedImages = customUploader
					.state()
					.get("selection")
					.map((item) => {
						item.toJSON();
						return item;
					});
				// reset results
				imageGalleriesInputValue = [];
				$(".ef-cpt-meta-img-galleries__list").html("");
				selectedImages.map((image) => {
					$(".ef-cpt-meta-img-galleries__list").append(
						'<li data-id="' +
							image.id +
							'"><span style="background-image:url(' +
							image.attributes.url +
							')"></span></li>'
					);
					// and to hidden field
					imageGalleriesInputValue.push(image.id);
				});
				// refresh sortable
				$(".ef-cpt-meta-img-galleries__list").sortable("refresh");
				// add the IDs to the hidden field value
				$(".ef-cpt-meta-img-galleries__input_hidden").val(
					imageGalleriesInputValue.join()
				);
			});

			customUploader.on("open", function () {
				var selection = customUploader.state().get("selection");
				imageGalleriesInput = $(
					".ef-cpt-meta-img-galleries__input_hidden"
				);
				imageGalleriesInputValue = imageGalleriesInput.val().split(",");
				imageGalleriesInputValue.forEach(function (id) {
					var attachment = wp.media.attachment(id);
					attachment.fetch();
					if (attachment) {
						selection.add([attachment]);
					}
				});
			});

			customUploader.open();
		});
	};

	var run = () => {
		cptInputImageGalleries();
	};

	return {
		run,
	};
};

export default cptInputHandler;
