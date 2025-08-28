#include "global.hpp"

int main() {
	//const char* host="ovh.dl.sourceforge.net";
	//const char* host="heanet.dl.sourceforge.net";
	const char* host="freefr.dl.sourceforge.net";//no date :p
	const char* path="/project/mingw/";
	try {
		boost::asio::io_service io_service;

		// Get a list of endpoints corresponding to the server name.
		tcp::resolver resolver(io_service);
		tcp::resolver::query query(host, "http");
		tcp::resolver::iterator endpoint_iterator = resolver.resolve(query);
		tcp::resolver::iterator endpoint;

		// Try each endpoint until we successfully establish a connection.
		tcp::socket socket(io_service);
		boost::system::error_code error = boost::asio::error::host_not_found;
		while (error && endpoint_iterator != endpoint)
		{
			socket.close();
			socket.connect(*endpoint_iterator++, error);
		}
		if (error)
		throw boost::system::system_error(error);

		// Form the request. We specify the "Connection: close" header so that the
		// server will close the socket after transmitting the response. This will
		// allow us to treat all data up until the EOF as the content.
		boost::asio::streambuf request;
		std::ostream request_stream(&request);
		request_stream << "GET " << path << " HTTP/1.0\r\n";
		request_stream << "Host: " << host << "\r\n";
		request_stream << "Accept: */*\r\n";
		request_stream << "Connection: close\r\n\r\n";

		// Send the request.
		boost::asio::write(socket, request);

		// Read the response status line. The response streambuf will automatically
		// grow to accommodate the entire line. The growth may be limited by passing
		// a maximum size to the streambuf constructor.
		boost::asio::streambuf response(512);
		boost::asio::read_until(socket, response, "\r\n");

		// Check that response is OK.
		std::istream response_stream(&response);
		std::string http_version;
		response_stream >> http_version;
		unsigned int status_code;
		response_stream >> status_code;
		std::string status_message;
		std::getline(response_stream, status_message);
		if (!response_stream || http_version.substr(0, 5) != "HTTP/")
		{
			std::cout << "Invalid response\n";
			return 1;
		}
		if (status_code != 200)
		{
			std::cout << "Response returned with status code " << status_code << "\n";
			return 1;
		}

		// Read the response headers, which are terminated by a blank line.
		boost::asio::read_until(socket, response, "\r\n\r\n");

		// Process the response headers.
		std::string header;
		while (std::getline(response_stream, header) && header != "\r") {
			std::cout << header << "\n";
		}
		std::cout << "\n";

		// Write whatever content we already have to output.
		/*if (response.size() > 0) {
			//std::cout << &response;
			std::istream is(&response);
			string line;
			while (std::getline(is, line) && line!="\r" && line!="\r\n" && line!="\n") {
				fprintf(stderr, "[DEBUG] len = '%d' str='%s'\n", line.size(), line.c_str() );
				//std::cout << line << endl;
			}
		}//*/
		
		boost::regex expression("<td><a href=\"([^/\"]+)/\">\\s*([^/<\"]+)/\\s*</a></td><td(?:[^>]+)>\\s*([0-9]{2}-[A-Za-z]{3}-[0-9]{2,4} [0-9]{2}:[0-9]{2}(?:-[0-9]{2})?)\\s*</td>", boost::regex_constants::perl);
		boost::regex expression2("<a href=\"([^/\"]+)/\">\\s*([^/\"]+)/\\s*</a>\\s+([0-9]{2}-[A-Za-z]{3}-[0-9]{2,4} [0-9]{2}:[0-9]{2}(?:-[0-9]{2})?)\\s+");
		boost::regex expression3("<li><a href=\"([^/\"]+)/\">\\s*([^/\"]+)/\\s*</a>\\s*([0-9]{2}-[A-Za-z]{3}-[0-9]{2,4} [0-9]{2}:[0-9]{2}(?:-[0-9]{2})?)?\\s*</li>");
		
		string line;
		// Read until EOF, writing data to output as we go.
		std::string::const_iterator start, end;
		//while (boost::asio::read(socket, response, boost::asio::transfer_at_least(1), error)) {
		do { //already have a partial response from when we read the header
			//printf("[DEBUG] loop 0\n");
			std::istream is(&response);
			if (std::getline(is, line)) {//don't do it more than once, read_until take cares of the buffer, that prevent from having a partial line
				//printf("[DEBUG] loop 1\n");
				start = line.begin();
				end = line.end();
				/*if(*end==0) {
					//workaround
					end=(line.begin()+line.size()-1);
					fprintf(stderr, "[DEBUG] workaround for *end==0 : size='%d', end='%d'\n", line.size(), *end);
				}//*/
				boost::match_results<std::string::const_iterator> what;
				//apache table extended view
				while(regex_search(start, end, what, expression, boost::match_extra))
				{
					fprintf(stderr, "---\n");
					//printf("[DEBUG] loop 2\n");
					fprintf(stderr, "[INFO] path '%s'\n", what[1].str().c_str());
					fprintf(stderr, "[INFO] directory '%s'\n", what[2].str().c_str());
					fprintf(stderr, "[INFO] date '%s'\n", what[3].str().c_str());
					start = what[0].second;
				}
				//apache list extended view
				while(regex_search(start, end, what, expression2, boost::match_extra))
				{
					fprintf(stderr, "---\n");
					//printf("[DEBUG] loop 2\n");
					fprintf(stderr, "[INFO] path '%s'\n", what[1].str().c_str());
					fprintf(stderr, "[INFO] directory '%s'\n", what[2].str().c_str());
					fprintf(stderr, "[INFO] date '%s'\n", what[3].str().c_str());
					start = what[0].second;
				}
				//apache list standard/minimal view
				while(regex_search(start, end, what, expression3, boost::match_extra))
				{
					fprintf(stderr, "---\n");
					//printf("[DEBUG] loop 2\n");
					fprintf(stderr, "[INFO] path '%s'\n", what[1].str().c_str());
					fprintf(stderr, "[INFO] directory '%s'\n", what[2].str().c_str());
					fprintf(stderr, "[INFO] date '%s'\n", what[3].str().c_str());//no date
					start = what[0].second;
				}
			}
		}
		while (boost::asio::read_until(socket, response, std::string("\n"), error));
		fprintf(stderr, "---\n");
		if (error != boost::asio::error::eof) {
			throw boost::system::system_error(error);
		}
	}
	catch (std::exception& e)
	{
		std::cout << "Exception: " << e.what() << "\n";
	}

	cout << "done" << endl;
	usleep(1000);//workaround
	return EXIT_SUCCESS;
}
//
