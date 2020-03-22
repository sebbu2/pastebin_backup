#include <iostream>
#include <unordered_map>
#include <cmath>

namespace ast {

typedef std::unordered_map<char, double> data;

#pragma STDC FENV_ACCESS ON

template<int D> struct val { 
	double operator() (const data&) const { return D; } 
};
template<char I> struct mis {
	double operator()(const data& v) const { return v.at(I); } 
};
template<typename Expr> struct minus {
	double operator()(const data& v) const { return -(Expr()(v));  }
};
template<typename Expr1, typename Expr2> struct add {
	double operator()(const data& v) const { return Expr1()(v)+Expr2()(v);  }
};
template<typename Expr1, typename Expr2> struct sub {
	double operator()(const data& v) const { return Expr1()(v)-Expr2()(v);  }
};
template<typename Expr1, typename Expr2> struct mul {
	double operator()(const data& v) const { return Expr1()(v)*Expr2()(v); }
};
template<typename Expr1, typename Expr2> struct div {
	double operator()(const data& v) const { return Expr1()(v)/Expr2()(v); }
};
template<typename Expr1, typename Expr2> struct mod {
	double operator()(const data& v) const { return fmod(Expr1()(v),Expr2()(v)); }
};

}

using namespace ast;

int main() {
	add<
		mis<'a'>, 
		mul<
			val<2>, 
			mis<'b'>
		>
	> expression;

	mod<mis<'a'>, mis<'b'> > exp2;
	double a=1, b=2; //or whatever
	std::cout << expression({ { 'a', a }, { 'b', b } }) << '\n';
	a=7, b=3;
	std::cout << exp2({ { 'a', a }, { 'b', b } }) << '\n';
	return 0;
}