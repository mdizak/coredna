
# CoreDNA Test

If you're not part of the CoreDNA team, please ignore this repo.  Apologies, but that online test was not accessible via screen reader as I was unable to find the textbox to enter code.  Hope this is acceptable, and I did this instead.  Answers to written questions are below, and 
the four functions asked for are in the /src/answers.php file, alongside the HTTP client that is there.  Hope that's acceptable to you.


### Answers to Written Questions

**What is the difference between git merge and git rebase?**

merge puts every commit together from all branches, whereas rebase creates more of a sequential, linear commit history making it appear there's only one branch.  I think, but could be wrong on that.


**What is the process to revert a commit that has already has been pushed and made public?**

reset or revert, all depending on what you're looking to do.


**What kind of data is suitable for SQL databases and what kind of data is more suitable for NoSQL databases?**

SQL = ~98% of the operations you encounter.  NoSQL databases such as MongoDB should only ever be used for certain use cases where you have a requirement for a schemaless database, such as maybe medical / insurance / spolice records.  Databases where you need the ability to throw in unstructured, arbitrary data into a record.  All other operations should be in a proper RDBMS such as PostgreSQL though, as it will provide better performance as a structured database schema allows the database engine to optomize queries far better than a NoSQL database engine can.  Other special use cases for NoSQL are things such as elastic search.

**What is a constraint in SQL?**

Joins two columns of the same data type (eg. integer) from two different tables together.  So if you have a parent and child row joined by a foreign key, and use cascading, if you delete the parent row the child row will magically disappear as well.  Also, you can't insert a child row without the appropriate parent row existing.  in short, helps both, to ensure structural integrity of a database, and helps allow other developers to easily see the architecture of the database schema.


**What are the best strategies to protect your application against XSS attacks?**

Use a proper template engine that validates and sanitizes all user input, and what gets outputted to the browser.  Don't just echo things to the browser willy nilly. :)


**Why in 2020 PHP is a good language for web-applications?**

For web development?  Nothing comes close to PHP, especially now that we have PHP v7.4 and 8.0 coming out this winter.  Since the advent of PHP v7.0 (and later 7.4, we've had a nice ~65% boost in performance, making PHP blazing fast, and far faster than Python or Ruby.  It's also now a strongly typed language, and contains all fundamentals of a strong language (inheritance, traits, interfaces, etc.).  Boasts a huge community, has active core dev team now under a tight release schedule, we have Composer / packagist, and the list goes on.  Plus that new Demo language just came out, which will probably at least somewhat fracture the NodeJS community, all the while the PHP community seems to be reamining strong and going full steam ahead.  PHP rocks!


### PSR12 Compliance

For all intents my code is PSR12 compliant, albeit with two exceptions, which I can obviously change if necessary.  These two exceptions are:

1.  Everything is lowercase, as that's just what I've been using since my Perl days, when I was a kid 20 years ago first learning to write code.
2. I don't indent between class declarations, because PSR12 also states it's only one class per-file, so don't see the point especially since php-documentor can still read the files just fine.


