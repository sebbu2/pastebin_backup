switch(bytesToWrite) {
	default:
		fprintf(stderr, "[ERROR] impossible error.\n");
		return NULL;
	case 4:
		result[j]=((chr | byteMark) & byteMask);
		++j;
		chr>>=6;
	case 3:
		result[j]=((chr | byteMark) & byteMask);
		++j;
		chr>>=6;
	case 2:
		result[j]=((chr | byteMark) & byteMask);
		++j;
		chr>>=6;
	case 1:
		result[j]=(chr | firstByteMark[bytesToWrite]);
		++j;
}