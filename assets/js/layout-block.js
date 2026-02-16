( function ( wp ) {
	if (
		! wp ||
		! wp.blocks ||
		! wp.element ||
		! wp.data ||
		! wp.components ||
		! ( wp.blockEditor || wp.editor )
	) {
		return;
	}

	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var InspectorControls = ( wp.blockEditor || wp.editor ).InspectorControls;
	var MediaUpload = ( wp.blockEditor || wp.editor ).MediaUpload;
	var MediaUploadCheck = ( wp.blockEditor || wp.editor ).MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Button = wp.components.Button;
	var Notice = wp.components.Notice;
	var __ = wp.i18n.__;

	var layouts = window.THEME_LAYOUTS || {};
	var layoutKeys = Object.keys( layouts );
	var defaultLayout = layoutKeys.length ? layoutKeys[ 0 ] : '';

	if ( ! defaultLayout ) {
		return;
	}

	function getLayoutOptions() {
		return layoutKeys.map( function ( slug ) {
			return {
				label: layouts[ slug ].label || slug,
				value: slug,
			};
		} );
	}

	function fieldControl( field, metaKey, value, onChange ) {
		var fieldType = field.type || 'text';
		var label = field.label || metaKey;

		if ( fieldType === 'textarea' ) {
			return el( TextareaControl, {
				key: metaKey,
				label: label,
				value: value || '',
				onChange: function ( nextValue ) {
					onChange( metaKey, nextValue );
				},
			} );
		}

		if ( fieldType === 'url' ) {
			return el( TextControl, {
				key: metaKey,
				type: 'url',
				label: label,
				value: value || '',
				onChange: function ( nextValue ) {
					onChange( metaKey, nextValue );
				},
			} );
		}

		if ( fieldType === 'image_id' ) {
			var imageId = parseInt( value, 10 ) || 0;

			return el(
				'div',
				{ key: metaKey, style: { marginBottom: '16px' } },
				el( 'p', { style: { marginBottom: '8px' } }, label ),
				el(
					MediaUploadCheck,
					null,
					el( MediaUpload, {
						allowedTypes: [ 'image' ],
						value: imageId,
						onSelect: function ( media ) {
							var nextId = media && media.id ? parseInt( media.id, 10 ) : 0;
							onChange( metaKey, nextId );
						},
						render: function ( mediaProps ) {
							return el(
								Fragment,
								null,
								el(
									Button,
									{
										variant: 'secondary',
										onClick: mediaProps.open,
									},
									imageId ? __( 'Bild ersetzen', 'wwd' ) : __( 'Bild waehlen', 'wwd' )
								),
								imageId
									? el(
											Button,
											{
												variant: 'link',
												isDestructive: true,
												onClick: function () {
													onChange( metaKey, 0 );
												},
												style: { marginLeft: '8px' },
											},
											__( 'Entfernen', 'wwd' )
									  )
									: null
							);
						},
					} )
				)
			);
		}

		return el( TextControl, {
			key: metaKey,
			label: label,
			value: value || '',
			onChange: function ( nextValue ) {
				onChange( metaKey, nextValue );
			},
		} );
	}

	registerBlockType( 'theme/layout', {
		apiVersion: 2,
		title: __( 'Layout', 'wwd' ),
		icon: 'screenoptions',
		category: 'design',
		attributes: {
			layout: {
				type: 'string',
				default: 'one-img-layout',
			},
		},
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var className = props.className;
			var layout = attributes.layout && layouts[ attributes.layout ] ? attributes.layout : defaultLayout;
			var selectedLayout = layouts[ layout ] || null;
			var metaFields = selectedLayout && selectedLayout.meta ? selectedLayout.meta : {};
			var meta = useSelect( function ( select ) {
				return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
			}, [] );
			var editorDispatch = useDispatch( 'core/editor' );

			function onMetaChange( key, nextValue ) {
				var nextMeta = Object.assign( {}, meta );
				nextMeta[ key ] = nextValue;
				editorDispatch.editPost( { meta: nextMeta } );
			}

			var fieldControls = Object.keys( metaFields ).map( function ( metaKey ) {
				var field = metaFields[ metaKey ] || {};
				var rawValue = Object.prototype.hasOwnProperty.call( meta, metaKey ) ? meta[ metaKey ] : '';
				return fieldControl( field, metaKey, rawValue, onMetaChange );
			} );

			var previewLines = Object.keys( metaFields )
				.filter( function ( metaKey ) {
					return '' !== String( meta[ metaKey ] || '' ).trim();
				} )
				.slice( 0, 3 )
				.map( function ( metaKey ) {
					var field = metaFields[ metaKey ] || {};
					return ( field.label || metaKey ) + ': ' + String( meta[ metaKey ] );
				} );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{
							title: __( 'Layout Einstellungen', 'wwd' ),
							initialOpen: true,
						},
						el( SelectControl, {
							label: __( 'Layout-Vorlage', 'wwd' ),
							value: layout,
							options: getLayoutOptions(),
							onChange: function ( nextLayout ) {
								setAttributes( { layout: nextLayout } );
							},
						} ),
						fieldControls
					)
				),
				el(
					'div',
					{
						className: className + ' theme-layout-block-preview',
						style: {
							border: '1px solid #dcdcde',
							padding: '16px',
							borderRadius: '4px',
						},
					},
					el( 'strong', null, __( 'Layout:', 'wwd' ) + ' ' + ( selectedLayout ? selectedLayout.label : layout ) ),
					previewLines.length
						? el(
								'ul',
								{ style: { marginTop: '8px', marginBottom: 0, paddingLeft: '18px' } },
								previewLines.map( function ( line, index ) {
									return el( 'li', { key: String( index ) }, line );
								} )
						  )
						: el(
								Notice,
								{
									status: 'info',
									isDismissible: false,
								},
								__( 'Die finale Ausgabe wird serverseitig im Frontend gerendert.', 'wwd' )
						  )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
