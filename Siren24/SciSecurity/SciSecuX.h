
#ifdef __cplusplus
extern "C" {
#endif

#define	TYPE_SEED		"SEED"
#define	TYPE_AES		"AES"
#define	TYPE_HMAC		"HMAC"

#define	JOB_ENC			1

#define	BUFFSIZE		1024 * 4

void SeedEnc(char *pszRes, char *pszData, int len, int flag, char *pszIV, char *pszKey);
void SeedDec(char *pszRes, char *pszData, int len, int flag, char *pszIV, char *pszKey);
char *Encryption(char *sPlainData);
char *Decryption(char *sEncryptData);
char *HMacEncript(unsigned char* text, int text_len, int flag, char *pszKey);
int  HMacCompare(char *cHexa, char *cOrg, char *pszKey);

char *EncKey = "D02D169CBEA2A9F6DA5FEEDE56B2705C";

#ifdef __cplusplus
}
#endif

