AJAX Push API
=============

This API written in PHP and Javascript can be used for faster handling of messages between Client and Server, while not spending resources in lots of HTTP requests.
It's easy to use and a small documentation of every function is available right above it's definition, on the respective file.

The database structure is simple to set-up and flexible. A general-purpose structure is available on the 'default-structure.sql' file, that comes with this package.
You can basically add any column to the table you are using for messages, since the code is aleady ready for custom columns. You only *must* remember to include the following colums:

* channel	( VARCHAR )	( Any size, will be used for storing channel names )
* body		( TEXT )	( Will be used for storing the message contents )
* time		( INT )		( Any size over 10 must do the work. In the general-puspose I'm using 64 anyways. )

In the general-purpose you will find a 'meta' column, it can be used to store any type of data you want... So if you want to store a 'Sender ID' on it, feel free to.

This API can also be used not only for AJAX/Javascript, but basically any type of software or language that have support internet connection (and can do it in a Asynchronous manner).
All you need to do is adapt the Javascript code to the language you want to develop and it shall work great.