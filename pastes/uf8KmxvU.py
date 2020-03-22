def function()
	n=int(input(""))
	a=int(input(""))
	b=int(input(""))
	liste = []
	comptage = []

	for k in range(n):
		valeur = int(input(""))
		liste.append(valeur)

	for p in range(b-a+1):
		comptage.append(0)
	#comptage = [0] * range(b-a+1)
	#comptage = [0 for i in range(b-a+1)]

	for i in range(len(liste)):
		comptage[liste[i]-a] += 1

	for j in range(a,b+1):
		print("nombre de", j, " : ", comptage[j-a])
	#for j in range(b-a+1)
	#	print("nombre de", j+a, " : ", comptage[j])