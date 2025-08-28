#include <stdio.h>
#include <stdlib.h>

/*	moore
x	x	x
x	o	x
x	x	x
*/
/*	von neumann
#	x	#
x	o	x
#	x	#
*/
/*3.2.1*/
void dimensionnement(int *n, int *m);
void initialisation(int **tab, int n, int m);
int nb_generation();
void affiche(int **tab, int n, int m);
void affiche_fichier(int **tab, int n, int m, char *nom);
void saisie_B_M(int B[9]);
void saisie_S_M(int S[9]);

/*3.2.2*/
int nb_voisins_M(int **tab, int i, int j);
void duplication(int **tab1, int **tab2, int n, int m);
void generation_suivante_M(int **tab1, int **tab2, int n, int m, int B[9], int S[9]);

/*3.2.3*/
void jeu_M(int **tab1, int n, int m, int B[9], int S[9], int ng);

/*3.2.4*/
void jeu_conway(int **tab1, int n, int m, int ng);/*B3/S23*/
void jeu_fredkin_M(int **tab1, int n, int m, int ng);/*B1357/S1357*/

/*3.3*/
/*von neumann*/
int nb_voisins_VN(int **tab, int i, int j);
void generation_suivante_VN(int **tab1, int **tab2, int n, int m, int B[9], int S[9]);
/*B13/S13*/
	/*TODO*/

/*4.1*/
/*3 états*/
	/*TODO*/

/*4.2*/
/*fantome*/
	/*TODO*/

int main(void) {
	return EXIT_SUCCESS;
}
/**/
