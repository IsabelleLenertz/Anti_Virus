# Anti_Virus
The perfect web based tool to make sure your file is indeed a virus

For my server side web development class final project, Prof. Ditroia required students to choose from different projects. One piqued my interest right away, the one no student had chosen before:  building a web based static antivirus scanner for PE files. I divided the problem into four components: requirement gathering, design, implementation, and testing.
This project’s expectation simply stated to implement the features common to the other projects, so I had to detail all of them to determine the requirements before starting the research. I first read a book recommended by Prof. DiTroia that described three algorithms for signature-based scanning: Aho-Corasick, Veldman, and Wu-Manber. The first one stood out to me for its use of finite automata as I enjoy applying theoretical concepts to concrete projects. I continued by a short review of recent literature and found an article focusing on detecting malicious PE files using their headers. Resolved to turn in on time a functioning program, and realistic about my other deadlines, I divided up the functionalities implementations: first the header based analysis tool with the website around it, then the Aho-Corasick algorithm. To tackle the first task, I transposed my C++ bitwise header manipulation, acquired a few years back when working on a data obfuscation program, to web development. Next, using my information security background, I created an authentication mechanism with randomly salted and hashed passwords. I did not have time to implement the signature detection properly but was able  to learn some CSS to make the UI presentable. Testing and penetration testing did not reveal vulnerabilities. Despite being the first student to submit this topic since Prof. Ditroya has been teaching, I developed a web-based antivirus exceeding expectations in about a week.