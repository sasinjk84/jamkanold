#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "SciSecuX.h"

int main(int argc, char *argv[])
{
	char Type[32] = {0,};
	char Iv[17] = {0,};
	char Data[BUFFSIZE] = {0,};
    char HashData[BUFFSIZE] = {0,};
	char ResEnc[BUFFSIZE] = {0,};
	char ResDec[BUFFSIZE] = {0,};
	char szTmp[128] = {0,};
	int  nJob;
	int  nFlag;

    char *pEncData;
    char *pDecData;
	
	char _inputKey[17] = {0,};

    if (argc < 2) {
		printf("[Manual] SciSecuX SEED/AES/HMAC\n");
        return 0 ;
    }

	sprintf(Type, "%s", argv[1]);

    if(!strcmp(Type, TYPE_SEED)) {
		
		nJob = atoi(argv[2]);
		nFlag = atoi(argv[3]);
		
		// Seed Key Type only zero value
		if(nFlag != 0 && nFlag != 1 && nFlag != 2) {
			printf("Invalid Seed Key Type (only zero,one,two value)");
			return 0;
		}
		
		if(nJob == JOB_ENC) { // is 1
			/**
			  * Argument Description
			  * index : 0 ==> Module Name
			  * index : 1 ==> Algorithmus Type (SEED or HMAC or AES)
			  * index : 2 ==> Encrypt or Decrypt Type
			  * index : 3 ==> Seed Key Type ( zero,one,else value )
			  * index : 4 ==> Plain String
			  * index : 5 ==> Encrypt Key String
			**/
		
			//check argument length
			if(argc != 6) {
				printf("Invalid Argument length (Six argument)");
				return 0;
			}
			
			sprintf(Data, "%s" , argv[4]);
			sprintf(_inputKey, "%s" , argv[5]);
			
			SeedEnc(ResEnc, Data, strlen(Data), nFlag, Iv, _inputKey);
			printf("%s", ResEnc);
		} else if(nJob == JOB_DEC){ // is 2
			/**
			  * Argument Description
			  * index : 0 ==> Module Name
			  * index : 1 ==> Algorithmus Type (SEED or HMAC or AES)
			  * index : 2 ==> Encrypt or Decrypt Type
			  * index : 3 ==> Seed Key Type 
			  * index : 4 ==> IV Value
			  * index : 5 ==> Encrypted String 
			  * index : 6 ==> Encrypt Key String
			**/
			
			//check argument length
			if(argc != 7) {
				printf("Invalid Argument length (Seven argument)");
				return 0;
			}
			
			//IV Value check
			sprintf(szTmp, "%s", argv[4]);

			if(strlen(szTmp) < 16) {
				memcpy(Iv, szTmp, strlen(szTmp));
				memset(Iv+strlen(szTmp), 0x30, 16-strlen(szTmp));
				Iv[16] = 0x00;
			}
			else if(strlen(szTmp) > 16) {
				memcpy(Iv, szTmp+(strlen(szTmp)-16), 16);
				Iv[16] = 0x00;
			}
			else {
				strcpy(Iv, szTmp);
			}
			
			sprintf(Data, "%s", argv[5]);
			sprintf(_inputKey, "%s" , argv[6]);
			
			SeedDec(ResDec, Data, strlen(Data), nFlag, Iv, _inputKey);
			printf("%s", ResDec);
		
		} else {
			printf("Invalid Argument value %d",nJob);
			return 0;
		}
		
    } else if(!strcmp(Type, TYPE_AES)) {
		if (argc < 4) {
			printf("[Manual] Encript => SciSecuX AES 1 Data\n");
			printf("[Manual] Decrypt => SciSecuX AES 2 Data\n");
			return 0 ;
		}

		nJob = atoi(argv[2]);
		sprintf(Data, "%s", argv[3]);

		if(nJob == JOB_ENC) {
			pEncData = Encryption(Data);
			printf("%s", pEncData);

			if(pEncData) free(pEncData);
		}
		else {
			pDecData = Decryption(Data);
			printf("%s", pDecData);

			if(pDecData) free(pDecData);
		}
    } else if(!strcmp(Type, TYPE_HMAC)) {
		
		nJob = atoi(argv[2]);
		
		if(nJob == JOB_HMAC) {
			/**
			  * Argument Description
			  * index : 0 ==> Module Name
			  * index : 1 ==> Algorithmus Type (SEED or HMAC or AES)
			  * index : 2 ==> HMAC or HMAC Compare Type
			  * index : 3 ==> Seed Key Type (zero,one, else value )
			  * index : 4 ==> Mac target string 1
			  * index : 5 ==> Mac target string 2
			**/
			
			nFlag = atoi(argv[3]);
			
			// Seed Key Type only zero value
			if(nFlag != 0 && nFlag != 1 && nFlag != 2) {
				printf("Invalid Seed Key Type (only zero,one,two value)");
				return 0;
			}
			
			if(argc != 6) {
				printf("Invalid Argument length");
				return 0;
			}
			
			sprintf(Data, "%s", argv[4]);
			sprintf(_inputKey, "%s" , argv[5]);
			
			pEncData = HMacEncript((unsigned char*)Data, strlen(Data), nFlag, _inputKey);
			printf("%s", pEncData);

			if(pEncData) free(pEncData);
			
		}else {
			/**
			  * Argument Description
			  * index : 0 ==> Module Name
			  * index : 1 ==> Algorithmus Type (SEED or HMAC or AES)
			  * index : 2 ==> HMAC or HMAC Compare Type
			  * index : 3 ==> Mac target string
			  * index : 4 ==> Maced value string
			  * index : 5 ==> Seed Key string
			**/
		
			sprintf(Data, "%s", argv[3]);
			sprintf(HashData, "%s", argv[4]);
			sprintf(_inputKey, "%s" , argv[5]);
			
			printf("%d", HMacCompare(HashData, Data, _inputKey));
			
		}
    }

   return 0;
}
