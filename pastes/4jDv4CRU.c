#include <stdio.h>
#include <stdlib.h>

struct MaClasse;

typedef struct MaClasse
{
	void (*init)(struct MaClasse** this);
	void (*free)(struct MaClasse** this);
	void (*print)(void);
} MaClasse;

void MaClasse_init(struct MaClasse** this);
void MaClasse_free(struct MaClasse** this);
void MaClasse_print();

void MaClasse_init(struct MaClasse** this)
{
	*this = (MaClasse*) calloc(1, sizeof(MaClasse));
	(*this)->init = &MaClasse_init;
	(*this)->free = &MaClasse_free;
	(*this)->print = &MaClasse_print;
}

void MaClasse_free(struct MaClasse** this)
{
	free(*this);
	*this = NULL;
}

void MaClasse_print()
{
	printf("Hello World!\n");
}

int main(void)
{
	MaClasse *a;
	MaClasse_init(&a);
	a->print();
	a->free(&a);
	return EXIT_SUCCESS;
}
