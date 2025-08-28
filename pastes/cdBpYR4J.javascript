function input(){
	//var RayLeft = new THREE.Raycaster(THREE.Vector3(player.position.x,player.position.y-0.15, player.position.z ),THREE.Vector3(0,0,0.1));
	//var RayRight = new THREE.Raycaster(THREE.Vector3(player.position.x,player.position.y-0.15, player.position.z ),THREE.Vector3(0,0,-0.1));
	
	
	
	var leftpnt = new THREE.Vector3( -0.1,0,0 );
	var rightpnt= new THREE.Vector3( 0.1,0,0 );
	var uppnt	= new THREE.Vector3( 0,0,-0.1 );
	var downpnt	= new THREE.Vector3( 0,0,0.1 );


	/// experimental raycast test
	var startPnt = new THREE.Vector3( player.position.x,player.position.y-0.2, player.position.z );
	var endPnt = new THREE.Vector3( 0,-0.1,0 );
	//cube.position.set(player.position.x,player.position.y, player.position.z);
	var testRay = new THREE.Raycaster(startPnt,endPnt);
	testRay.set(startPnt,endPnt);
	var testIntersects = testRay.intersectObjects(scene.children);
	if ( testIntersects.length > 0) {
		//alert("this got trigerd");
		setMaterial(cube,Matswap);
		//testIntersects[ 1 ].object.material.color.set( 0xff0000 );
		player.position.y += 0.01;
	}
	else{
		setMaterial(cube,material);
		player.position.y += -0.01;
	}

	///
	leftRay = new THREE.Raycaster(startPnt,leftpnt);
	leftRay.set(startPnt,leftpnt);
	leftIntersect	= leftRay.intersectObjects(scene.children);
	if (keyboard.pressed("left") && leftIntersect.length < 1) 
{
player.position.x += -0.1;
startPnt = new THREE.Vector3( player.position.x,player.position.y-0.2, player.position.z );
}
	rightRay= new THREE.Raycaster(startPnt,rightpnt);
	rightRay.set(startPnt,rightpnt);
	rightIntersect	= rightRay.intersectObjects(scene.children);
	if (keyboard.pressed("right")&& rightIntersect.length < 1)
{
player.position.x +=0.1;
startPnt = new THREE.Vector3( player.position.x,player.position.y-0.2, player.position.z );
}
	upRay	= new THREE.Raycaster(startPnt,uppnt);
	upRay.set(startPnt,uppnt);
	upIntersect		= upRay.intersectObjects(scene.children);
	if (keyboard.pressed("up")	 && upIntersect.length < 1)
{
player.position.z += -0.1;
startPnt = new THREE.Vector3( player.position.x,player.position.y-0.2, player.position.z );
}
	downRay	= new THREE.Raycaster(startPnt,downpnt);
	downRay.set(startPnt,downpnt);
	downIntersect	= downRay.intersectObjects(scene.children);
	if (keyboard.pressed("down") && downIntersect.length < 1)
{
player.position.z += 0.1;
//no need for it, no further tests
}
	///

	//if (upIntersect.length > 0) {
	//	player.position.z = upIntersect[0].point.z + 0.01;
	//};
}
