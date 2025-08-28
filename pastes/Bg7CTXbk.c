#include <stdlib.h>
#include <string.h>
#include <stdio.h>

struct s1
{
	int a;
	float b;
	char* name;
	struct s2* s2;
	void (*init)(struct s1*);
	void (*free)(struct s1*);
};

struct s2
{
	short c;
	double d;
	char* desc;
	struct s1* s1;
	void (*init)(struct s2*);
	void (*free)(struct s2*);
};

void s1_free(struct s1* s1)
{
	if(s1==NULL) return;
	printf("s1_free\n");
	s1->a=0;
	s1->b=0.0;
	free(s1->name);
	struct s2* s2=s1->s2;
	s1->s2=NULL;
	s1->init=NULL;
	s1->free=NULL;
	if(s2!=NULL) if(s2->free!=NULL) s2->free(s2);
}

void s2_free(struct s2* s2)
{
	if(s2==NULL) return;
	printf("s2_free\n");
	s2->c=0;
	s2->d=0.0;
	free(s2->desc);
	struct s1* s1=s2->s1;
	s2->s1=NULL;
	s2->init=NULL;
	s2->free=NULL;
	if(s1!=NULL) if(s1->free!=NULL) s1->free(s1);
}

int main(int argc, char* argv[])
{
	struct s1 s1;
	
	s1.a=5;
	s1.b=3.14;
	s1.name=strdup("coucou");
	s1.free=s1_free;
	
	struct s2 s2;
	
	s2.desc=strdup("Hello World!");
	s2.free=s2_free;
	
	s1.s2=&s2;
	s2.s1=&s1;
	
	if(s1.free!=NULL) s1.free(&s1);
	if(s2.free!=NULL) s2.free(&s2);
	return EXIT_SUCCESS;
}
//
