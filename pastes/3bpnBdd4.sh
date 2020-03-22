#!/bin/bash
## add :
## export BASHPID=$$
## to ~/.bashrc if bash < 4.0-alpha

#fonction pour quitter correctement, peu importe le mode d'appel (source ou non)
function quitter() {
	# $BASHPID est le pid de la console bash
	# $$ est le pid du processus courant
	# $BASH_SUBSHELL vaux 0 si on est dans la console bash
	if [ $BASHPID -eq $$ ]
	#if [ $BASH_SUBSHELL -eq 0 ]
	then
		return
	else
		exit 1
	fi
}

#variables

#mode debug, 0 = off, 1 = on
#debug mode means it should be run as a separate process
#normal mode means it should be run as same process, with . path/file
DEBUG=1
#contenu de la ligne retournée par READ
REPLY=''
#code de retour de read (0 si normal, 1 si EOF)
ret=0

#afficher path
echo -e "\e[31mPATH=\e[0m"
echo $PATH

#vérifier mode d'appel
if [ $DEBUG -eq 1 ]
then
	if [ $BASHPID -eq $$ ]
	#if [ $BASH_SUBSHELL -eq 0 ]
	then
		echo -e '\e[1;31mERROR : launch with ./pathmod.sh while in DEBUG MODE\e[0m'
		quitter;
	fi
elif [ $DEBUG -eq 0 ]
then
	if [ $BASHPID -ne $$ ]
	#if [ $BASH_SUBSHELL -eq 0 ]
	then
		echo -e '\e[1;31mERROR : launch with . ./pathmod.sh OR source ./pathmod.sh\e[0m'
		quitter;
	fi
else
	echo -e '\e[1;31mERROR : invalide DEBUG value\e[0m'
	quitter;
fi

#sauvegarde l'ancienne valeur du séparateur de champ
IFS_OLD=$IFS
#séparateur de champ
IFS=':'
export IFS
#création d'un tableau
declare -a dirs=($PATH)
dirsize=${#dirs[@]}

#vérifie nombre de dossier : ERREUR si == 1 et PATH contient :
if [ ${#dirs[@]} -eq 1 -a `expr index "$PATH" ':'` -ne 0 ]
then
	echo $dirs
	echo -e "\e[1;31mERROR : 1 element only\e[0m"
	quitter;
fi

#affiche la liste des dossiers
function dirs_show() {
	echo -e "\e[32mDIRS=\e[0m"
	echo "Number of items in original array: ${#dirs[@]}"
	for i in ${!dirs[@]}
	do
		printf "%4d = %s\n" $i ${dirs[$i]}
	done
}
dirs_show
echo ''

#menu
while true
do
	echo -e "\e[34mChoix\e[0m de l'opération à éffectuer [help pour l'aide]"
	read -e
	ret=$?
	#quitter la boucle
	if [ $ret -eq 1 ]
	then
		break;
	elif [ "$REPLY" == "quit" -o "$REPLY" == "exit" ]
	then
		break
	elif [ "$REPLY" == "show" ]
	then
		dirs_show
	#ajouter un dossier avant un dossier existant (2 args)
	elif [ "${REPLY%% *}" == "add_before" ]
	then
		id1=`expr index "$REPLY" ' '`
		string=${REPLY#* }
		id2=`expr index "$string" ' '`
		idx=${string%% *}
		string=${string#* }
		if [ $id1 -eq 0 -o $id2 -eq 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		#vérifier s'il y a rien avant
		if [ $idx -eq 0 ]
		then
			dirs=( "$string" ${dirs[@]} )
		else
			dirs=( ${dirs[@]:0:$idx-1} "$string" ${dirs[@]:$idx} )
		fi
		IFS=:
		dirsize=${#dirs[@]}
	#ajouter un dossier après un dossier existant (2 args)
	elif [ "${REPLY%% *}" == "add_after" ]
	then
		id1=`expr index "$REPLY" ' '`
		string=${REPLY#* }
		id2=`expr index "$string" ' '`
		idx=${string%% *}
		string=${string#* }
		if [ $id1 -eq 0 -o $id2 -eq 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		#vérifier s'il y a rien après
		if [ $idx -eq ${#dirs} ]
		then
			dirs=( ${dirs[@]:0:$idx+1} "$string" )
		else
			dirs=( ${dirs[@]:0:$idx+1} "$string" ${dirs[@]:$idx+1} )
		fi
		IFS=:
		dirsize=${#dirs[@]}
	#supprime un dossier
	elif [ "${REPLY%% *}" == "delete" ]
	then
		id1=`expr index "$REPLY" ' '`
		idx=${REPLY#* }
		id2=`expr index "$idx" ' '`
		if [ $id1 -eq 0 -o $id2 -ne 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		#vérifier qu'il y a rien avant
		if [ $idx -eq 0 ]
		then
			dirs=${dirs[@]:1}
		else
			#vérifier qu'il y a rien après
			if [ $idx -eq ${#dirs} ]
			then
				dirs=( ${dirs[@]:0:$idx} )
			else
				dirs=( ${dirs[@]:0:$idx} ${dirs[@]:$idx+1} )
			fi
		fi
		IFS=:
		dirsize=${#dirs[@]}
	#déplace un dossier d'une ou plusieurs position vers le haut
	elif [ "${REPLY%% *}" == "moveup" ]
	then
		id1=`expr index "$REPLY" ' '`
		string=${REPLY#* }
		id2=`expr index "$string" ' '`
		idx=${string%% *}
		pos=${string#* }
		id3=`expr index "$pos" ' '`
		if [ $id1 -eq 0 -o $id2 -eq 0 -o $id3 -ne 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		if [ $idx -lt 0 -o $idx -ge $dirsize ]
		then
			echo -e "\e[1;31mERROR : incorrect index\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		if [ $((idx-$pos)) -lt 0 -o $((idx-$pos)) -ge $dirsize ]
		then
			echo -e "\e[1;31mERROR : incorrect diff value\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		dirs=( ${dirs[@]:0:$idx-$pos} ${dirs[$idx]} ${dirs[@]:$idx-$pos:$pos} ${dirs[@]:$idx+1} )
		IFS=:
	#déplace un dossier d'une ou plusieurs position vers le bas
	elif [ "${REPLY%% *}" == "movedown" ]
	then
		id1=`expr index "$REPLY" ' '`
		string=${REPLY#* }
		id2=`expr index "$string" ' '`
		idx=${string%% *}
		pos=${string#* }
		id3=`expr index "$pos" ' '`
		if [ $id1 -eq 0 -o $id2 -eq 0 -o $id3 -ne 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		if [ $idx -lt 0 -o $idx -ge $dirsize ]
		then
			echo -e "\e[1;31mERROR : incorrect index\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		if [ $((idx-$pos)) -lt 0 -o $((idx-$pos)) -ge $dirsize ]
		then
			echo -e "\e[1;31mERROR : incorrect diff value\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		dirs=( ${dirs[@]:0:$idx} ${dirs[@]:$idx+1:$pos} ${dirs[$idx]} ${dirs[@]:$idx+pos+1} )
		IFS=:
	#remplace le nom d'un dossier
	elif [ "${REPLY%% *}" == "update" -o "${REPLY%% *}" == "replace" ]
	then
		id1=`expr index "$REPLY" ' '`
		string=${REPLY#* }
		id2=`expr index "$string" ' '`
		idx=${string%% *}
		string=${string#* }
		echo "[DEBUG] $id1"
		echo "[DEBUG] $string"
		echo "[DEBUG] $id2"
		echo "[DEBUG] $idx"
		echo "[DEBUG] $string"
		if [ $id1 -eq 0 -o $id2 -eq 0 ]
		then
			echo -e "\e[1;31mERROR : incorrect number of arguments\e[0m"
			echo '' #blank line skipped because of continue
			continue
		fi
		IFS=
		#vérifier s'il y a rien avant
		if [ $idx -eq 0 ]
		then
			dirs=( "$string" ${dirs[@]:1} )
		else
			#dirs=( ${dirs[@]:0:$idx-1} "$string" ${dirs[@]:$idx} )
			#vérifier qu'il y a rien après
			if [ $idx -eq ${#dirs} ]
			then
				dirs=( ${dirs[@]:0:$idx} "$string" )
			else
				dirs=( ${dirs[@]:0:$idx} "$string" ${dirs[@]:$idx+1} )
			fi
		fi
		IFS=:
	#afficher l'aide
	elif [ "$REPLY" == "help" ]
	then
		echo -e "\e[34mLISTE DES COMMANDES :\e[0m"
		echo -e "show\tAffiche le contenu du tableau (PATH)"
		echo -e "quit\tQuitte le programme"
		echo -e "exit\tQuitte le programme"
		echo -e "help\tAffiche cette aide"
		echo -e "add_before(\$n,\$d)\tAjoute un dossier \$d avant le dossier n° \$n"
		echo -e "add_after(\$n,\$d)\tAjoute un dossier \$d après le dossier n° \$n"
		echo -e "delete(\$n)\tEfface le dossier n° \$n"
		echo -e "update(\$n,\$d)\tMet à jour le dossier \$d à la \$n position"
		echo -e "replace(\$n,\$d)\tMet à jour le dossier \$d à la \$n position"
		echo -e "move_up($n,$m)\tDeplace le dossier n° \$n de \$m position vers le haut"
		echo -e "move_down($n,$m)\tDeplace le dossier n° \$n de \$m position vers le bas"
	fi
	echo '' #blank line
done

#sauvegarde
IFS=:
#echo '{$dirs[@]}=' ${dirs[@]}
#echo '{$dirs[*]}=' ${dirs[*]}
PATH="${dirs[*]}"
export PATH

#restauration
IFS=$IFS_OLD
export IFS
#unsets
unset dirs dirsize
unset dirsize i id1 id2 idx ret string IFS_OLD DEBUG