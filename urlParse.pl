#!/usr/bin/perl -w

use strict; use warnings;
use HTML::TokeParser::Simple;
use encoding "utf-8";

my $myUrl=$ARGV[0];
my $myDataFile=$ARGV[1];

my $p = HTML::TokeParser::Simple->new(url => $myUrl);

my $level;

open (MYFILE, ">$myDataFile");
print MYFILE "";
close (MYFILE);

while (my $tag = $p->get_tag('td')) {
    my $class = $tag->get_attr('class');
    next unless defined($class) and $class eq 'header';

    $level += 1;

    while (my $token = $p->get_token) {
        $level += 1 if $token->is_start_tag('td');
        $level -= 1 if $token->is_end_tag('td');
	open (MYFILE, ">>$myDataFile");
	print MYFILE $token->as_is;
	close (MYFILE);
        unless ($level) {
            last;
        }
    }
}

exit 1;
