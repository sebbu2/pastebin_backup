#include <iostream>
#include "siunits.hpp"

using namespace std;

typedef unit<1,0,0,0,0,0,0,float> meter;
typedef unit<0,0,1,0,0,0,0,float> second;
typedef unit<1,0,-1,0,0,0,0,float> ms;

int main(void)
{
	meter distance=12;
	distance*=20;
	cout << distance << endl;
	second time=60;
	//auto speed=disance/time;
	ms speed=distance/time;
	cout << speed << endl;
	return 0;
}
//
