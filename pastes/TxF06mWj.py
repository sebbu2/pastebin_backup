# -*- coding: latin-1 -*-
"""
Created on Wed Mar 09 20:14:13 2016

@author: Clunet
"""

from tkFont import *;
from Tkinter import *;

#fond de page
fenetre = Tk()
fenetre.title("Bataille Navale")
canevas = Canvas(fenetre, width=1200, height=550, bg= "light blue")
canevas.create_line(600, 553, 600, 0, fill="white", width=100)
canevas.create_line(675, 50, 675, 0, fill="white", width=50)
canevas.create_line(0, 50, 0, 0, fill="white", width=100)
canevas.pack()

#plateau 1

#ligne horizontale et verticale
canevas.create_line(50, 3, 550, 3, width=3)
canevas.create_line(3, 550, 3, 50, width=3)

canevas.create_line(1200, 3, 700, 3, width=3)
canevas.create_line(650, 550, 650, 50, width=3)
for i in range(50,600,50):
    canevas.create_line(0, i, 550, i, width=3)
    canevas.create_line(i, 550, i, 0, width=3)
    canevas.create_line(1200, i, 650, i, width=3)
    canevas.create_line(650+i, 550, 650+i, 0, width=3)

#échelle
#for i in range(75, 600, 50):
for i in range(0, 10, 1):
    chiffre=chr(ord('1')+i)
    if chiffre==':':
        chiffre = "10"
    canevas.create_text(75+i*50, 25, font=("Comic Sans MS", 20), fill="Black", text=chr(ord('A')+i))
    canevas.create_text(25, 75+i*50, font=("Comic Sans MS", 20),fill="Black", text=chiffre)
    canevas.create_text(725+i*50, 25, font=("Comic Sans MS", 20), fill="Black", text=chr(ord('A')+i))
    canevas.create_text(675, 75+i*50, font=("Comic Sans MS", 20),fill="Black", text=chiffre)

#placement bateau

def placement():

    global bateaux

    bateaux = Toplevel()
    bateaux.title("Bateaux")  

    Label(bateaux, text="Coordonnées du torpilleur : ").grid(row=0)
    Label(bateaux, text="(exemple : A1, A2)").grid(row=1)
    Label(bateaux, text="Coordonnées du croiseur : ").grid(row=2)
    Label(bateaux, text="(exemple : A1, A2, A3)").grid(row=3)
    Label(bateaux, text="Coordonnées du sous-marin : ").grid(row=4)
    Label(bateaux, text="(exemple : A1, A2, A3)").grid(row=5)
    Label(bateaux, text="Coordonnées du contre-torpilleur : ").grid(row=6)
    Label(bateaux, text="(exemple : A1, A2, A3, A4)").grid(row=7)
    Label(bateaux, text="Coordonnées du porte-avion : ").grid(row=8)
    Label(bateaux, text="(exemple : A1, A2, A3, A4, A5)").grid(row=9)

    e1 = Entry(bateaux)
    e2 = Entry(bateaux)
    e3 = Entry(bateaux)
    e4 = Entry(bateaux)
    e5 = Entry(bateaux)

    e1.grid(row=0, column=1)
    e2.grid(row=2, column=1)
    e3.grid(row=4, column=1)        
    e4.grid(row=6, column=1)
    e5.grid(row=8, column=1)

    f1 = Button(bateaux, text ='Valider')
    f1.grid(row=0, column=2, padx=10, pady=2)
    f2 = Button(bateaux, text ='Valider')
    f2.grid(row=2, column=2, padx=10, pady=2)
    f3 = Button(bateaux, text ='Valider')
    f3.grid(row=4, column=2, padx=10, pady=2)
    f4 = Button(bateaux, text ='Valider')
    f4.grid(row=6, column=2, padx=10, pady=2)
    f5 = Button(bateaux, text ='Valider')
    f5.grid(row=8, column=2, padx=10, pady=2)

b1 = Button(fenetre, text ='Bateaux', command=placement)
b1.pack(side=LEFT, padx=5, pady=5)


"""
 Disposition aléatoire des bateaux
"""

import random 

dirs = [0, 1, 2, 3]
# 0 : haut
# 1 : gauche
# 2 : bas
# 3 : droite
def check_length(row, col, size):
    global list
    res2 = -1
    while res2==-1:
        # éviter de tjrs placer les bateaux dans le meme sens
        res = random.randint(0, len(dirs))
        if res==0:
            if row >= size-1:
                #placement en haut possible
                res2=0
        if res==1:
            if col >= size-1:
                #placement à gauche possible
                res2=1
        if res==2:
            if row <= len(list)-size:
                #placement en bas possible
                res2=2
        if res==3:
            if col <= len(list[0])-size:
                #placement à droite possible
                res2=3
    return res2

# 0 : fail
# 1 : ok
def check_avail(row, col, size, dir):
    global list
    for i in range(0, size):
        if dir==0:
            if list[row-i][col]!=0:
                return 0
        if dir==1:
            if list[row][col-i]!=0:
                return 0
        if dir==2:
            if list[row+i][col]!=0:
                return 0
        if dir==3:
            if list[row][col+i]!=0:
                return 0
    return 1

def list_fill(row, col, size, dir, nb):
    global list
    for i in range(0, size):
        if dir==0:
            list[row-i][col]=nb
        if dir==1:
            list[row][col-i]=nb
        if dir==2:
            list[row+i][col]=nb
        if dir==3:
            list[row][col+i]=nb
            

#coordonnées

# A-J 1-10
# i   j
# 0-9 0-9
# A1-J10 : (75+i*50, 50+j*50, 75+i*50, 100+j*50)

sizes = [2, 3, 3, 4, 5]

def grid_to_screen(row, col):
    coord = row*10 + col
    return (75+coord%10*50, 50+coord/10*50, 
            75+coord%10*50, 100+coord/10*50)

cond = True
while cond:
    try:
        list  = [[0 for i in range(10)] for j in range(10)]
        for i in range(0, 5):
            coord = random.randint(0,100)
            # tirage d'un nombre entre 00 et 99 inclus
            # le premier chiffre (coord/10) est la ligne
            # le deuxième chiffre (coord%10) est la colonne
            row=coord/10
            col=coord%10
            print row, col
            dir=check_length(row, col, sizes[i])
            if dir == -1:
                raise NameError('placement pas possible')
            print dir
            if check_avail(row, col, sizes[i], dir)==0:
                raise NameError('placement occupé')
            
            list_fill(row, col, sizes[i], dir, i+1)
            # taille des bateaux : 2, 3, 3, 4, 5
            # list = 1 : 2
            # list = 2 : 3
            # list = 3 : 3
            # list = 4 : 4
            # list = 5 : 5
            cond = False
    except NameError as exp:
        print exp.message
        cond = True

colors=["yellow", "green", "red", "blue", "pink"]
for row in range(0, 10):
    for col in range(0, 10):
        # 0 = vide, 1-5 = bateaux
        bat = list[row][col]-1
        if bat!=-1:
            canevas.create_line(grid_to_screen(row, col), width=50, fill=colors[bat])

Button(fenetre, text ='Bouton 2').pack(side=LEFT, padx=5, pady=5)

fenetre.mainloop()
