#ifndef SIUNITS_HPP
#define SIUNITS_HPP

#include <string>
#include <iomanip>
#include <iostream>
#include <utility>

static const char* _length[2]={"","m"};
static const char* _mass[2]={"","g"};
static const char* _time[2]={"","s"};
static const char* _current[2]={"","A"};
static const char* _temperature[2]={"","C"};
static const char* _substance[2]={"","mol"};
static const char* _luminosity[2]={"","cd"};

template<int length, int mass, int time, int current, int temperature, int substance, int luminosity,
#ifdef EXTRA
#endif
	typename type
	>
class unit;
/*
template<int length, int mass, int time, int current, int temperature, int substance, int luminosity,
#ifdef EXTRA
#endif
	typename type,
	int length2, int mass2, int time2, int current2, int temperature2, int substance2, int luminosity2>
	unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type>
	operator/(
		const unit<length,mass,time,current,temperature,substance,luminosity,type>& v1,
		const unit<length2,mass2,time2,current2,temperature2,substance2,luminosity2,type>& v2);//*/


template<int length, int mass, int time, int current, int temperature, int substance, int luminosity,
#ifdef EXTRA
#endif
	typename type
	>
class unit
{
private:
	unit& operator=(const type& v) { value=v.value; return (*this); }
	
	template<int length2, int mass2, int time2, int current2, int temperature2, int substance2, int luminosity2>
	unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type>
	operator/=(const unit<length2,mass2,time2,current2,temperature2,substance2,luminosity2,type>& v2);
	
	unit& operator*=(const unit& v);
	//unit& operator/=(const unit& v);
protected:
	type value;
public:
	typedef unit<length,mass,time,current,temperature,substance,luminosity,type> ctype;//*/
	unit():value(){}
	unit(const type& v):value(v){}
	unit(const unit& v):value(v.value){}
	unit(const unit&& v):value(std::move(v.value)){}
	~unit(){}
	const type& Value() const { return value; }
	unit& operator=(const unit& v) { value=v.value; return (*this); }
	unit& operator=(const unit&& v) { value=std::move(v.value); return (*this); }
	unit& operator+=(const unit& v) { value+=v.value; return (*this); }
	unit& operator-=(const unit& v) { value-=v.value; return (*this); }
	unit& operator%=(const unit& v) { value%=v.value; return (*this); }
	unit& operator*=(const type& v) { value*=v; return (*this); }
	unit& operator/=(const type& v) { value/=v; return (*this); }
	unit& operator%=(const type& v) { value%=v; return (*this); }
	template<int length2, int mass2, int time2, int current2, int temperature2, int substance2, int luminosity2>
	friend unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type>
	operator/(
		const unit<length,mass,time,current,temperature,substance,luminosity,type>& v1,
		const unit<length2,mass2,time2,current2,temperature2,substance2,luminosity2,type>& v2)
		{
			unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type> v(v1.value/v2.Value());
			return v;
		}//*/
	/*template<int length2, int mass2, int time2, int current2, int temperature2, int substance2, int luminosity2>
	friend unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type>
	operator/(
		const unit<length2,mass2,time2,current2,temperature2,substance2,luminosity2,type>& v1,
		const unit<length,mass,time,current,temperature,substance,luminosity,type>& v2)
		{
			unit<length-length2,mass-mass2,time-time2,current-current2,temperature-temperature2,substance-substance2,luminosity-luminosity2,type> v(v1.value()/v2.value());
			return v;
		}//*/
	friend std::ostream& operator<<(std::ostream& o, const unit& v) {
		o << v.value;
		
		o << _length[length!=0];
		o << (length<0?"-":"");
		if(length<0||length>1)
			o << abs(length);
		
		o << _mass[mass!=0];
		o << (mass<0?"-":"");
		if(mass<0||mass>1)
			o << abs(mass);
		
		o << _time[time!=0];
		o << (time<0?"-":"");
		if(time<0||time>1)
			o << abs(time);
		
		return o; }
};

template<int length, int mass, int time, int current, int temperature, int substance, int luminosity, class type>
	unit<length,mass,time,current,temperature,substance,luminosity,type>
	operator+(
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v1,
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v2) {
		unit<length,mass,time,current,temperature,substance,luminosity,type> v(v1); v+=v2; return v;
	}
template<int length, int mass, int time, int current, int temperature, int substance, int luminosity, class type>
	unit<length,mass,time,current,temperature,substance,luminosity,type>
	operator-(
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v1,
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v2) {
		unit<length,mass,time,current,temperature,substance,luminosity,type> v(v1); v-=v2; return v;
	}
template<int length, int mass, int time, int current, int temperature, int substance, int luminosity, class type>
	unit<length,mass,time,current,temperature,substance,luminosity,type>
	operator%(
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v1,
	const unit<length,mass,time,current,temperature,substance,luminosity,type>& v2) {
		unit<length,mass,time,current,temperature,substance,luminosity,type>& v(v1); v%=v2; return v;
	}


#endif
//
