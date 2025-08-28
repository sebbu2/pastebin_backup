void bullyMethodology() {
	do {
		if (bullies.size() >= 1){
			System.out.print(hurtfulWords);
			System.out.print(internalizedRacism);
			System.out.print(thoughtlessBile);
			if (bully.aggression >= 2){
				hurt(bully, victim);
			}
		}else{
			Bully b = new Bully(individual, victim);
			bullies.add(b);
		}
		if (victim.isStillResisting == true){
			bully.aggression++;
			if (victim.fightingAbility >= bully.fightingAbility) {
				makeFalseAccusation(bully, victim);
				cowardlyAttack(bully, victim);
				spreadLies(bully, victim);
			}
		}
	} while (victim.alive == true);
}
//Of course, a bully is not an individual but a group. And the group dynamics mean all bully the ones who stand out so that a group can be a group. There can be no unity without someone to band together to hurt.
