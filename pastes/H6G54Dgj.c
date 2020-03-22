#include <stdio.h>
#include <string.h>
/* windows-only */
#include <windows.h>

char const * const netfx_1_0_key = "Software\\Microsoft\\Active Setup\\Installed Components\\{78705f0d-e8db-4b2d-8193-982bdda15ecd}";
char const * const netfx_1_0_value = "Version";

typedef struct version
{
	char const * const key;
	char const * const name;
	DWORD type;
	char exist;
	int size;
	char * value;
} version;

version netfx_1_0 = { "Software\\Microsoft\\.NETFramework\\Policy\\v1.0", "3705", REG_SZ, '\0', 0, NULL };
version netfx_1_0a = { "Software\\Microsoft\\Active Setup\\Installed Components\\{78705f0d-e8db-4b2d-8193-982bdda15ecd}", "Version", REG_SZ, '\0', 0, NULL };
version netfx_1_0b = { "Software\\Microsoft\\Active Setup\\Installed Components\\{FDC11A6F-17D1-48f9-9EA3-9051954BAA24}", "Version", REG_SZ, '\0', 0, NULL };
version netfx_1_1 = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v1.1.4322", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_1_1b = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v1.1.4322", "SP", REG_DWORD, '\0', 0, NULL };
version netfx_1_1x64 = { "Software\\Wow6432Node\\Microsoft\\NET Framework Setup\\NDP\\v1.1.4322", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_1_1x64b = { "Software\\Wow6432Node\\Microsoft\\NET Framework Setup\\NDP\\v1.1.4322", "SP", REG_DWORD, '\0', 0, NULL };
version netfx_2_0 = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v2.0.50727", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_2_0b = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v2.0.50727", "SP", REG_DWORD, '\0', 0, NULL };
version netfx_3_0 = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v3.0", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_3_0b = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v3.0", "SP", REG_DWORD, '\0', 0, NULL };
version netfx_3_5 = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v3.5", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_3_5b = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v3.5", "SP", REG_DWORD, '\0', 0, NULL };
version netfx_4_0c = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v4\\Client", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_4_0cb = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v4\\Client", "Version", REG_SZ, '\0', 0, NULL };
version netfx_4_0f = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v4\\Full", "Install", REG_DWORD, '\0', 0, NULL };
version netfx_4_0fb = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v4\\Full", "Version", REG_SZ, '\0', 0, NULL };
version netfx_4_5 = { "Software\\Microsoft\\NET Framework Setup\\NDP\\v4\\Full", "Release", REG_DWORD, '\0', 0, NULL };

#define vc 18
version* my_list[vc] = {
	&netfx_1_0, /*+1*/
	&netfx_1_0a,
	&netfx_1_0b,
	&netfx_1_1,
	&netfx_1_1b,
	&netfx_1_1x64,
	&netfx_1_1x64b,
	&netfx_2_0,
	&netfx_2_0b,
	&netfx_3_0,
	&netfx_3_0b,
	&netfx_3_5,
	&netfx_3_5b,
	&netfx_4_0c,
	&netfx_4_0cb,
	&netfx_4_0f,
	&netfx_4_0fb,
	&netfx_4_5 /*+1*/
};
char const * const my_ver[vc] = {
	"1.0 ",
	"1.0 (te)",
	"1.0 (mc)",
	"1.1",
	"1.1",
	"1.1 x64",
	"1.1 x64",
	"2.0",
	"2.0",
	"3.0",
	"3.0",
	"3.5",
	"3.5",
	"4.0 client",
	"4.0 client",
	"4.0 full",
	"4.0 full",
	"4.5"
};

int my_init(version* ver)
{
	int res = 0;
	HKEY hk;
	char* data = (char*)calloc(32+1, sizeof(char*));
	int dwSize = 32;
	if (RegOpenKeyEx(HKEY_LOCAL_MACHINE, ver->key, 0, KEY_READ, &hk) != ERROR_SUCCESS)
	{
		res = 1;
	}
	
	if (RegQueryValueEx(hk, ver->name, 0, &ver->type, (LPBYTE)data, &dwSize) != ERROR_SUCCESS)
	{
		free(data);
		ver->value = NULL;
		res = 2;
	}
	else
	{
		ver->exist = '\1';
		ver->size = dwSize;
		ver->value = data;
	}
	RegCloseKey(hk);
	return res;
}

int my_free(version* ver)
{
	if(ver==NULL) return 0;
	if(ver->value==NULL) return 0;
	free(ver->value);
	return 0;
}

int main(int argc, char* argv[])
{
	printf("%s\n", "Hello World!");
	/* HINSTANCE dllPtr = LoadLibrary("kernel32.dll"); */
	
	int i = 0;
	
	for(i=0;i<vc;i++)
	{
		my_init(my_list[i]);
	}
	
	for(i=0;i<vc;i++)
	{
		if(my_list[i]->exist == '\0') continue;
		if (my_list[i]->type == REG_SZ) {
			printf("VER %s = %.*s\n", my_ver[i], my_list[i]->size, my_list[i]->value);
		}
		else if (my_list[i]->type == REG_DWORD) {
			/* printf("%d\n", my_list[i]->size); */
			if(my_list[i]->size == 4) {
				printf("VER %s = %d\n", my_ver[i], *((int*)my_list[i]->value));
			}
			else {
				printf("VER %s\n", my_ver[i]);
			}
		}
	}
	
	for(i=0;i<vc;i++)
	{
		my_free(my_list[i]);
	}
	
	/* FreeLibrary(dllPtr); */
	return 0;
}
/**/