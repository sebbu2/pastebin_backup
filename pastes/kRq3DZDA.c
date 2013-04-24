switch(bytesToWrite) {
	default:
		fprintf(stderr, "[ERROR] impossible error.\n");
		return NULL;
	case 4:
		result[j+3]=((chr | byteMark) & byteMask);
		chr>>=6;
	case 3:
		result[j+2]=((chr | byteMark) & byteMask);
		chr>>=6;
	case 2:
		result[j+1]=((chr | byteMark) & byteMask);
		chr>>=6;
	case 1:
		result[j]=(chr | firstByteMark[bytesToWrite]);
		j+=bytesToWrite;
}