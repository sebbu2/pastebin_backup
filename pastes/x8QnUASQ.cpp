#include <iostream>
#include <unordered_map>

typedef std::unordered_map<char, double> data;

template<int D> struct val { 
    double operator() (const data&) const { return D; } 
};
template<char I> struct mis {
    double operator()(const data& v) const { return v.at(I); } 
};
template<typename Expr1, typename Expr2> struct add {
    double operator()(const data& v) const { return Expr1()(v)+Expr2()(v);  }
};
template<typename Expr1, typename Expr2> struct mul {
    double operator()(const data& v) const { return Expr1()(v)*Expr2()(v); }
};

int main() {
    add<
            mis<'a'>, 
            mul<
                    val<2>, 
                    mis<'b'>
            >
    > expression;

    double a=1, b=2; //or whatever
    std::cout << expression({ { 'a', a }, { 'b', b } }) << '\n';
    return 0;
}