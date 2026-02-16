( function( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element || ! wp.components ) {
		return;
	}

	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var components = wp.components;
	var blockEditor = wp.blockEditor || wp.editor;

	if ( ! blockEditor ) {
		return;
	}

	var InspectorControls = blockEditor.InspectorControls;
	var SelectControl = components.SelectControl;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var PanelBody = components.PanelBody;
	var Button = components.Button;
	var BaseControl = components.BaseControl;

	var definitions = ( window.wwdLayoutBlockData && window.wwdLayoutBlockData.definitions ) || {};

	function cloneData( value ) {
		return JSON.parse( JSON.stringify( value ) );
	}

	function isEmptyValue( value ) {
		return '' === value || null === value || 'undefined' === typeof value;
	}

	function getDefaultItem( field ) {
		var row = {};
		var fields = Array.isArray( field.fields ) ? field.fields : [];

		fields.forEach( function( subfield ) {
			if ( ! subfield || ! subfield.key ) {
				return;
			}
			if ( 'repeater' === subfield.type ) {
				row[ subfield.key ] = [];
				return;
			}
			row[ subfield.key ] = isEmptyValue( subfield.default ) ? '' : subfield.default;
		} );

		return row;
	}

	function fieldControl( field, value, onChange ) {
		var label = field.label || field.key;
		var type = field.type || 'text';

		if ( 'textarea' === type ) {
			return el( TextareaControl, {
				label: label,
				value: isEmptyValue( value ) ? '' : value,
				onChange: onChange
			} );
		}

		if ( 'url' === type ) {
			return el( TextControl, {
				label: label,
				type: 'url',
				value: isEmptyValue( value ) ? '' : value,
				onChange: onChange,
				placeholder: 'https://'
			} );
		}

		return el( TextControl, {
			label: label,
			value: isEmptyValue( value ) ? '' : value,
			onChange: onChange
		} );
	}

	function repeaterControl( field, value, onChange ) {
		var items = Array.isArray( value ) ? value : [];
		var max = parseInt( field.max, 10 );

		if ( Number.isNaN( max ) || max < 1 ) {
			max = 10;
		}

		function updateItem( index, key, nextValue ) {
			var nextItems = cloneData( items );
			nextItems[ index ] = nextItems[ index ] || {};
			nextItems[ index ][ key ] = nextValue;
			onChange( nextItems );
		}

		function removeItem( index ) {
			var nextItems = cloneData( items );
			nextItems.splice( index, 1 );
			onChange( nextItems );
		}

		function addItem() {
			if ( items.length >= max ) {
				return;
			}
			var nextItems = cloneData( items );
			nextItems.push( getDefaultItem( field ) );
			onChange( nextItems );
		}

		var children = items.map( function( item, index ) {
			var subfields = Array.isArray( field.fields ) ? field.fields : [];
			var rowChildren = subfields.map( function( subfield ) {
				var subValue = item && ! isEmptyValue( item[ subfield.key ] ) ? item[ subfield.key ] : '';
				return el(
					'div',
					{ key: field.key + '-' + index + '-' + subfield.key },
					fieldControl( subfield, subValue, function( nextValue ) {
						updateItem( index, subfield.key, nextValue );
					} )
				);
			} );

			rowChildren.push(
				el(
					Button,
					{
						key: field.key + '-' + index + '-remove',
						variant: 'secondary',
						isDestructive: true,
						onClick: function() {
							removeItem( index );
						}
					},
					'Eintrag entfernen'
				)
			);

			return el(
				'div',
				{
					key: field.key + '-row-' + index,
					style: {
						padding: '12px',
						marginBottom: '12px',
						border: '1px solid #dcdcde',
						borderRadius: '4px'
					}
				},
				el( 'p', { style: { marginTop: 0, fontWeight: '600' } }, ( field.label || field.key ) + ' #' + ( index + 1 ) ),
				rowChildren
			);
		} );

		children.push(
			el(
				Button,
				{
					key: field.key + '-add',
					variant: 'primary',
					onClick: addItem,
					disabled: items.length >= max
				},
				'Eintrag hinzufügen'
			)
		);

		return el(
			BaseControl,
			{ label: field.label || field.key },
			children
		);
	}

	function renderLayoutFields( definition, data, setData ) {
		if ( ! definition || ! Array.isArray( definition.fields ) ) {
			return null;
		}

		return definition.fields.map( function( field ) {
			var fieldValue = data && 'object' === typeof data ? data[ field.key ] : '';

			if ( 'repeater' === field.type ) {
				return el(
					'div',
					{ key: field.key },
					repeaterControl( field, fieldValue, function( nextValue ) {
						var nextData = cloneData( data || {} );
						nextData[ field.key ] = nextValue;
						setData( nextData );
					} )
				);
			}

			return el(
				'div',
				{ key: field.key },
				fieldControl( field, fieldValue, function( nextValue ) {
					var nextData = cloneData( data || {} );
					nextData[ field.key ] = nextValue;
					setData( nextData );
				} )
			);
		} );
	}

	registerBlockType( 'theme/layout', {
		title: 'Layout einfügen',
		icon: 'screenoptions',
		category: 'design',
		attributes: {
			layout: {
				type: 'string',
				default: ''
			},
			data: {
				type: 'object',
				default: {}
			}
		},
		edit: function( props ) {
			var attributes = props.attributes || {};
			var layout = attributes.layout || '';
			var data = attributes.data || {};
			var definition = definitions[ layout ] || null;
			var options = [ { label: 'Layout wählen', value: '' } ];

			Object.keys( definitions ).forEach( function( key ) {
				var item = definitions[ key ] || {};
				options.push( {
					label: item.label || key,
					value: key
				} );
			} );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Layout einfügen', initialOpen: true },
						el( SelectControl, {
							label: 'Layout',
							value: layout,
							options: options,
							onChange: function( nextLayout ) {
								props.setAttributes( {
									layout: nextLayout,
									data: {}
								} );
							}
						} )
					)
				),
				el(
					'div',
					{ className: props.className },
					el( SelectControl, {
						label: 'Layout',
						value: layout,
						options: options,
						onChange: function( nextLayout ) {
							props.setAttributes( {
								layout: nextLayout,
								data: {}
							} );
						}
					} ),
					definition
						? el(
							'div',
							{ className: 'wwd-layout-block-fields' },
							renderLayoutFields( definition, data, function( nextData ) {
								props.setAttributes( { data: nextData } );
							} )
						)
						: el( 'p', null, 'Bitte ein Layout auswählen.' )
				)
			);
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp );
