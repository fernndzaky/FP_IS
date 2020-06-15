import pandas as pd
from scipy import sparse
from sklearn.metrics.pairwise import cosine_similarity
from tabulate import tabulate
pd.set_option('display.max_columns', None)  # or 1000

ratings = pd.read_csv('ratings.csv')
movies = pd.read_csv('movies.csv')
ratings = pd.merge(movies, ratings).drop(['genres','timestamp'],axis=1)
#print(ratings.head())

user_ratings = ratings.pivot_table(index=['userId'],columns=['title'],values='rating')
#print(user_ratings)
# drop less then 10
user_ratings = user_ratings.dropna(thresh=5,axis=1).fillna(0)
f = open("generated_title_list.txt", "w")
#print('file opened')
#for col in user_ratings:
    #print(col)
    #f.write(col)
    #f.write("\n")
#f.close()
#print(user_ratings)

item_similarity_df = user_ratings.corr(method='pearson')
#print(item_similarity_df.head(50))

def get_similar_movies(movie_name, user_rating):
    similar_score = item_similarity_df[movie_name]*(user_rating-2.5)
    similar_score = similar_score.sort_values(ascending=False)
    return similar_score

user_input = []
file = open("input2.txt", "r")
for line in file.read().split("\n"):
    if line != "":
        token = line.split('|')
        user_input.append((token[0],int(token[1])))
similar_movies = pd.DataFrame()

for movie, rating in user_input:
    similar_movies = similar_movies.append(get_similar_movies(movie, rating))
print('data frame is')
#print(similar_movies.columns)
print('getting similar movies')
f = open("result2.txt", "w")

# print(similar_movies.head())
similar_movie = similar_movies.sum().sort_values(ascending=False)
print(similar_movie)
#for movie in str(similar_movie):
    #print(movie)

#for i in range (len(similar_movie)):
    #i+=1
f.write(str(similar_movie))
f.close()

result = []
file = open("result2.txt", "r")
counter = 0
for line in file.read().split("\n"):
    if(counter == 4):
        break
  
    if line != "":
        token = line.split('  ')
        result.append(token[0])
        counter += 1
    
file.close()

f = open("result2.txt", "w")

for i in range (len(result)):
    f.write(result[i])
    f.write("\n")

    i+=1
f.close()

#print(result)
    #f.write("\n")
# print()
#print(len(similar_movie))