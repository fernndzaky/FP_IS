#!/usr/bin/env python

import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity
user_input = []
file = open("input.txt", "r")
for line in file.read().split("\n"):
	user_input.append(line)

###### helper functions. Use them when needed #######
def get_title_from_index(index):
	return df[df.index == index]["title"].values[0]

def get_index_from_title(title):
	return df[df.title == title]["index"].values[0]
##################################################

##Step 1: Read CSV File
df = pd.read_csv("movie_dataset.csv")
#print (df.head())

#print columns using df.columns()
#i = 0
#f = open("movie_title.txt", "w")
#print(df['original_title'].count())
#for i in range(df['original_title'].count()):
	#print(df['original_title'][i])
	#f.write(df['original_title'][i])
	#f.write("\n")
	#i+=1
#f.close()


##Step 2: Select Features and clear NaN data
features = ['keywords','cast','genres','director']

#clear NaN data
for feature in features:
	#fill all NaN with empty string
	df[feature] = df[feature].fillna('')

##Step 3: Create a column in DF which combines all selected features
def combine_features(row):
#use try and except to know which row is wrong
	try:
		return row['keywords'] + " " + row['cast'] + " " + row['genres'] + " " + row['director']

	except:
		print("Error : ", row)
#combine vertically
df["combined_features"] = df.apply(combine_features, axis=1)
#print ("Combined features", df["combined_features"].head())


##Step 4: Create count matrix from this new combined column
#create cv object
cv = CountVectorizer()
#count num ber of text
count_matrix = cv.fit_transform(df["combined_features"])

##Step 5: Compute the Cosine Similarity based on the count_matrix
cosine_sim = cosine_similarity(count_matrix)

movie_user_likes = user_input[0]

## Step 6: Get index of this movie from its title
movie_index = get_index_from_title(movie_user_likes)
similar_movies = list(enumerate(cosine_sim[movie_index]))
#print(sorted_similar_movies)


## Step 7: Get a list of similar movies in descending order of similarity score

sorted_similar_movies = sorted(similar_movies, key= lambda x:x[1], reverse=True)

## Step 8: Print titles of first 50 movies
i = 0
f = open("result.txt", "w")
print('file opened')
for movie in sorted_similar_movies:
	print(get_title_from_index(movie[0]))

	f.write(get_title_from_index(movie[0]))
	f.write("\n")

	i += 1
	if i >50:
		break
f.close()
