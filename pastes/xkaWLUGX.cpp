#include <iostream>
#include "siunits.hpp"

using namespace std;

int main(void)
{
	unit<1,0,0,0,0,0,0,float> meter=12;
	meter*=20;
	cout << meter << endl;
	unit<0,0,1,0,0,0,0,float> time=60;
	//auto speed=meter/time;
	unit<1,0,-1,0,0,0,0,float> speed=meter/time;
	cout << speed << endl;
	return 0;
}
//
