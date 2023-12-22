const cptInputHandler = ( $, wp ) => {
	const cptInputImageGalleries = () => {
		const imageGalleriesAddBtn = $( '.ef-cpt-meta-img-galleries__add_btn' );
		const imageGalleriesResult = $( '.ef-cpt-meta-img-galleries__list' );
		let imageGalleriesInput = $(
			'.ef-cpt-meta-img-galleries__input_hidden'
		);
		let imageGalleriesInputValue =
			imageGalleriesInput.val()?.split( ',' ) ?? '';

		imageGalleriesResult.sortable( {
			cursor: '-webkit-grabbing', // mouse cursor
			stop( event, ui ) {
				ui.item.removeAttr( 'style' );

				const sort = new Array(); // array of image IDs
				const container = $( this );

				// each time after dragging we resort our array
				container.find( 'li' ).each( () => {
					sort.push( $( this ).attr( 'data-id' ) );
				} );
				// add the array value to the hidden input field
				container.parent().next().val( sort.join() );
			},
		} );

		imageGalleriesAddBtn.click( ( e ) => {
			e.preventDefault();

			const isSingleImage =
				$( e.target ).attr( 'data-attr-is-single' ) === 'true'
					? true
					: false;

			const list = $( e.target ).siblings(
				'.ef-cpt-meta-img-galleries__list'
			);
			const inputHidden = $( e.target ).siblings(
				'.ef-cpt-meta-img-galleries__input_hidden'
			);

			const customUploader = wp.media( {
				title: 'Insert images',
				library: {
					type: 'image',
				},
				button: {
					text: 'Use these images',
				},
				multiple: ! isSingleImage,
			} );

			customUploader.on( 'select', function () {
				// get selected images and rearrange the array
				const selectedImages = customUploader
					.state()
					.get( 'selection' )
					.map( ( item ) => {
						item.toJSON();
						return item;
					} );
				// reset results
				imageGalleriesInputValue = [];
				list.html( '' );
				selectedImages.forEach( ( image ) => {
					list.append(
						'<li data-id="' +
							image.id +
							'"><span style="background-image:url(' +
							image.attributes.url +
							')"></span></li>'
					);
					// and to hidden field
					imageGalleriesInputValue.push( image.id );
				} );
				// refresh sortable
				list.sortable( 'refresh' );
				// add the IDs to the hidden field value
				inputHidden.val( imageGalleriesInputValue.join() );
			} );

			customUploader.on( 'open', function () {
				const selection = customUploader.state().get( 'selection' );
				imageGalleriesInput = $(
					'.ef-cpt-meta-img-galleries__input_hidden'
				);
				imageGalleriesInputValue = imageGalleriesInput
					.val()
					.split( ',' );
				imageGalleriesInputValue.forEach( function ( id ) {
					const attachment = wp.media.attachment( id );
					attachment.fetch();
					if ( attachment ) {
						selection.add( [ attachment ] );
					}
				} );
			} );

			customUploader.open();
		} );
	};

	const run = () => {
		cptInputImageGalleries();
	};

	return {
		run,
	};
};

export default cptInputHandler;
