#include <vector>
#include <bitset>
#include <cstdio>

using namespace std;

template<int SIZE>
vector<int> eratosthenes()
{
    bitset<SIZE> sieve;
    vector<int> primes;
    sieve.set();

    for(int i=2; i<SIZE; i++)
        if (sieve[i]) {
            primes.push_back(i);
            for (int j = i*i; j<SIZE; j+=i)
                sieve[j] = false;
        }
    return primes;
}

int main(void)
{
	vector<int> era = eratosthenes<100>();
	for(unsigned int i=0;i<era.size();i++)
	{
		printf("%d\n", era[i]);
	}
	return 0;
}
