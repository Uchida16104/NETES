#include <cstdlib>
#include <iostream>
#include <string>

int main(int argc, char* argv[]) {
    std::string cmd = "/Users/hirotoshiuchida/NETES/target/release/netes-engine";

    if (argc > 1) {
        cmd += " ";
        cmd += argv[1];
    }

    int ret = system(cmd.c_str());
    return ret;
}