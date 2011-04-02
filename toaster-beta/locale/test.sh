#!/bin/sh

export LANGUAGE=$1
export TEXTDOMAINDIR="./"
export TEXTDOMAIN="messages"

gettext -s "Donate!"
