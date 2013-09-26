#include <cassert>
#include <cstdlib>
#include <cstdio>
#include <ctime>
//#include <iostream>
#include <cstring>
//#include <string>

extern char ** environ;

const int LOOP=100000;

inline void loop1(char *buf, clock_t t, int a, int i) {
	t=clock();
	for(a=0;a<LOOP;++a) {
		for(i=0;environ[i]!=NULL;++i) {
			if(strncmp(environ[i],"PATH=",5)==0) buf=environ[i]+5;
		}
	}
	t=clock()-t;
	printf("%s\n", buf);
	printf ("%d clicks (%f seconds)\n", (int)t, ((float)t)/CLOCKS_PER_SEC);
}

inline void loop2(char *buf, clock_t t, int a, int i, char* envp[]) {
	t=clock();
	for(a=0;a<LOOP;++a) {
		for(i=0;envp[i]!=NULL;++i) {
			if(strncmp(envp[i],"PATH=",5)==0) buf=envp[i]+5;
		}
	}
	t=clock()-t;
	printf("%s\n", buf);
	printf ("%d clicks (%f seconds)\n", (int)t, ((float)t)/CLOCKS_PER_SEC);
}

inline void loop3(char *buf, clock_t t, int a) {
	t=clock();
	for(a=0;a<LOOP;++a) {
		buf=getenv("PATH");
	}
	t=clock()-t;
	printf("%s\n", buf);
	printf ("%d clicks (%f seconds)\n", (int)t, ((float)t)/CLOCKS_PER_SEC);
}

int main(int argc, char* argv[], char* envp[]) {
	char *buf=NULL;
	clock_t t=0;
	int a=0;
	int i=0;
	if(argc==2) {
		if( strcmp(argv[1],"-v")==0 || strcmp(argv[1],"--version")==0 ) {
			printf("%s version 0.0.1\n", argv[0]);
		}
		if(argv[1][1]=='\0') {
			if(argv[1][0]=='1') loop1(buf, t, a, i);
			if(argv[1][0]=='2') loop2(buf, t, a, i, envp);
			if(argv[1][0]=='3') loop3(buf, t, a);
		}
		return 1;
	}
	return 0;
}
//