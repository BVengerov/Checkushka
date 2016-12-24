#!/bin/bash

# Define realpath func for compatibility with OSX shell
function realpath()
{
    echo "$(cd "$(dirname "$1")"; pwd)/$(basename "$1")"
}

cd "$1"

BRANCHPOINT=`git merge-base --fork-point master HEAD`
FILENAMES=`git diff $BRANCHPOINT HEAD --name-only | xargs realpath`

echo "$FILENAMES"