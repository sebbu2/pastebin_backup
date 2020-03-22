localStorage.setItem("userTypes", `class MyCustomFx {
	constructor(_key) {
		this.key = _key;
	}
	_read() {
		this._io = {};
        this._debug = {};
	}
	decode(src)
	{
		var len = src.length;
		var dest = new Uint8Array(len);
		var seed = this.key
		for (var i = 0; i < len; i++) {
			seed = (seed * 33 + 153) >> 0; // prevents overflow from uint32
			dest[i] = ( src[i] ^ seed );
		}
		return dest;
	}
}
this.MyCustomFx = MyCustomFx;`);