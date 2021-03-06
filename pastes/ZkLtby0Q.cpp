
	enum element {
		_UNUSED=0,
		_FILE=1,
		//1st level node
		MAP=10,
			//attributes
			MAP_VERSION,
			MAP_ORIENTATION,
			MAP_WIDTH,
			MAP_HEIGHT,
			MAP_TILEWIDTH,
			MAP_TILEHEIGHT,
			//subnodes
			MAP__PROPERTIES=20,
				//subnodes
				MAP__PROPERTIES__PROPERTY,
					//attributes
					MAP__PROPERTIES__PROPERTY_NAME,
					MAP__PROPERTIES__PROPERTY_VALUE,
			TILESET=30,
				//attributes
				TILESET_NAME,
				TILESET_FIRSTGID,
				TILESET_SOURCE,
				TILESET_TILEWIDTH,
				TILESET_TILEHEIGHT,
				TILESET_SPACING,
				TILESET_MARGIN,
				//subnodes
				TILESET__PROPERTIES=40,
					//subnodes
					TILESET__PROPERTIES__PROPERTY,
						//attributes
						TILESET__PROPERTIES__PROPERTY_NAME,
						TILESET__PROPERTIES__PROPERTY_VALUE,
				TILESET__IMAGE=50,
					//attributes
					TILESET__IMAGE_SOURCE,
					TILESET__IMAGE_TRANS,
					TILESET__IMAGE_WIDTH,
					TILESET__IMAGE_HEIGHT,
					TILESET__IMAGE_FORMAT,
					TILESET__IMAGE_ID,
				TILESET__TILEOFFSET=60,
					//attributes
					TILESET__TILEOFFSET_X,
					TILESET__TILEOFFSET_Y,
				TILESET__TILE=70,
					//attributes
					TILESET__TILE_ID,
					//subnodes
					TILESET__TILE__PROPERTIES=80,
						//subnodes
						TILESET__TILE__PROPERTIES__PROPERTY,
						//attributes
							TILESET__TILE__PROPERTIES__PROPERTY_NAME,
							TILESET__TILE__PROPERTIES__PROPERTY_VALUE,
			LAYER=90,
				//attributes
				LAYER_NAME,
				LAYER_WIDTH,
				LAYER_HEIGHT,
				LAYER_X,
				LAYER_Y,
				LAYER_VISIBLE,
				LAYER_OPACITY,
				//subnodes
				LAYER__DATA=100,
					//attributes
					LAYER__DATA_ENCODING,
					LAYER__DATA_COMPRESSION,
					//subnode
					LAYER__TILE=110,
						//attributes
						LAYER__TILE__GID,
				LAYER__PROPERTIES=120,
					//subnodes
					LAYER__PROPERTIES__PROPERTY,
						//attributes
						LAYER__PROPERTIES__PROPERTY_NAME,
						LAYER__PROPERTIES__PROPERTY_VALUE,
			OBJECTLAYER=130,
				//attributes
				OBJECTLAYER_NAME,
				OBJECTLAYER_WIDTH,
				OBJECTLAYER_HEIGHT,
				OBJECTLAYER_X,
				OBJECTLAYER_Y,
				OBJECTLAYER_VISIBLE,
				OBJECTLAYER_COLOR,
				//subnodes
				OBJECTLAYER__OBJECT=140,
					OBJECTLAYER__OBJECT_NAME,
					OBJECTLAYER__OBJECT_TYPE,
					OBJECTLAYER__OBJECT_X,
					OBJECTLAYER__OBJECT_Y,
					OBJECTLAYER__OBJECT_WIDTH,
					OBJECTLAYER__OBJECT_HEIGHT,
					OBJECTLAYER__OBJECT_GID,
					//subnodes
					OBJECTLAYER__OBJECT__POLYGON=150,
						//attributes
						OBJECTLAYER__OBJECT__POLYGON_POINTS,
					OBJECTLAYER__OBJECT__POLYLINE=160,
						//attributes
						OBJECTLAYER__OBJECT__POLYLINE_POINTS,
					OBJECTLAYER__OBJECT__PROPERTIES=170,
					//subnodes
					OBJECTLAYER__OBJECT__PROPERTIES__PROPERTY,
						//attributes
						OBJECTLAYER__OBJECT__PROPERTIES__PROPERTY_NAME,
						OBJECTLAYER__OBJECT__PROPERTIES__PROPERTY_VALUE,
				OBJECTLAYER__PROPERTIES=180,
					//subnodes
					OBJECTLAYER__PROPERTIES__PROPERTY,
						//attributes
						OBJECTLAYER__PROPERTIES__PROPERTY_NAME,
						OBJECTLAYER__PROPERTIES__PROPERTY_VALUE,
				IMAGELAYER=190,
					//attributes
					IMAGELAYER_NAME,
					IMAGELAYER_WIDTH,
					IMAGELAYER_HEIGHT,
					IMAGELAYER_X,
					IMAGELAYER_Y,
					IMAGELAYER_VISIBLE,
					//subnodes
					IMAGELAYER__IMAGE=200,
						//attributes
						IMAGELAYER__IMAGE_SOURCE,
						IMAGELAYER__IMAGE_TRANS,
						//IMAGELAYER__IMAGE_WIDTH,
						//IMAGELAYER__IMAGE_HEIGHT,
					IMAGELAYER__PROPERTIES=210,
						//subnodes
						IMAGELAYER__PROPERTIES__PROPERTY,
							//attributes
							IMAGELAYER__PROPERTIES__PROPERTY_NAME,
							IMAGELAYER__PROPERTIES__PROPERTY_VALUE,
		_LAST=220
	};