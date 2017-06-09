using JavCrawl.Dal.Context;
using JavCrawl.Models;
using JavCrawl.Models.DbEntity;
using JavCrawl.Utility.Context;
using JavCrawl.Utility;
using Microsoft.EntityFrameworkCore.Storage;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Dal.Implement
{
    public class DbRepository : IDbRepository
    {
        private readonly MySqlContext _dbContext;
        private readonly IFtpHelper _ftpHelper;
        private readonly IHtmlHelper _htmlHelper;

        public DbRepository(MySqlContext dbContext, IFtpHelper ftpHelper, IHtmlHelper htmlHelper)
        {
            _dbContext = dbContext;
            _ftpHelper = ftpHelper;
            _htmlHelper = htmlHelper;
        }

        public async Task<bool> RunJobCrawl()
        {
            var jobs = _dbContext.JobListCrawl
                        .OrderBy(x => x.ScheduleAt)
                        .FirstOrDefault(x => (x.StartAt == null && x.ScheduleAt <= DateTime.Now) || x.Always == true);

            if (jobs == null) return true;

            jobs.StartAt = DateTime.Now;

            await _dbContext.SaveChangesAsync();

            var movies = await _htmlHelper.GetJavHiHiMovies(jobs.Link);

            if (movies == null || movies.movies == null || movies.movies.Count == 0)
            {
                jobs.FinishAt = DateTime.Now;
                jobs.Complete = 0;
                jobs.UnComplete = 0;

                await _dbContext.SaveChangesAsync();

                return true;
            }

            var total = movies.movies.Count;

            var complete = await CrawlJavHiHiMovies(movies);

            jobs.FinishAt = DateTime.Now;
            jobs.Complete += complete;
            jobs.UnComplete += total - complete;

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<bool> SaveSchedule(JobListCrawl job)
        {
            if (job == null) return false;

            _dbContext.JobListCrawl.Add(job);

            await _dbContext.SaveChangesAsync();

            return true;
        }
        public IList<JobListCrawl> GetSchedule()
        {
            var results = _dbContext.JobListCrawl.OrderByDescending(x => x.ScheduleAt).Take(50);

            return results.ToList();
        }

        public async Task<int> CrawlJavHiHiMovies(JavHiHiMovies javHiHiMovies)
        {
            if (javHiHiMovies == null || javHiHiMovies.movies == null || javHiHiMovies.movies.Count == 0) return 0;

            var results = 0;

            foreach(var movie in javHiHiMovies.movies)
            {
                var isSave = await SaveJavHiHiMovie(movie);
                if (isSave) results++;
            }
            return results;
        }

        private async Task<int> GetCountry(string name)
        {
            var country = _dbContext.Countries.FirstOrDefault(x => x.Title.ToLower() == name.ToLower().Trim());

            if (country != null) return country.Id;

            var newCountry = new Countries
            {
                Title = name.Trim(),
                Slug = name.ToLower().Trim().Replace(" ", "-"),
                Seo = "{\"title\":\"" + name.Trim() + "\",\"desc\":\"" + name.Trim() + " porn videos, " + name.Trim() + " adult videos\",\"keyword\":\"" + name.Trim() + " porn video, " + name.Trim() + " adult videos\"}",
                Menu = 1,
                TitleAscii = name.Trim(),
                Status = 1,
                CreatedAt = DateTime.Now,
                Description = string.Format("" + name.Trim() + " porn video, " + name.Trim() + " adult videos", name.Trim())
            };

            await _dbContext.AddAsync(newCountry);

            await _dbContext.SaveChangesAsync();

            return newCountry.Id;
        }

        private int GetDirector(string name)
        {
            var dir = _dbContext.Directors.FirstOrDefault();

            return dir.Id;
        }

        private async Task<int> GetGenre(string name)
        {
            name = name.Replace("-", " ").ToTitleCase();

            var genre = _dbContext.Genres.FirstOrDefault(x => x.Title.ToLower() == name.ToLower().Trim());
            if (genre != null) return genre.Id;

            var newGenre = new Genres
            {
                Title = name.Trim(),
                TitleAscii = name.Trim(),
                Slug = name.ToLower().Trim().Replace(" ", "-"),
                Status = 1,
                ParentId = 0,
                Description = "Stunning models doing amazing Asian " + name.Trim() + " in hot scenes provided. Hot Japanese " + name.Trim() + " porn in good quality",
                Menu = 1,
                CreatedAt = DateTime.Now,
                Seo = "{\"title\":\"Asian " + name.Trim() + ", JAV " + name.Trim() + ", Free JAV HD\",\"desc\":\"Stunning models doing amazing Asian " + name.Trim() + " in hot scenes provided. Hot Japanese " + name.Trim() + " porn in good quality\",\"keyword\":\"asian " + name.Trim() + ", Japanese " + name.Trim() + ", Asian " + name.Trim() + ", Japanese " + name.Trim() + ", Best Asian " + name.Trim() + "\"}"
            };

            await _dbContext.AddAsync(newGenre);
            await _dbContext.SaveChangesAsync();

            return newGenre.Id;
        }

        private async Task<int> GetStar(string name)
        {
            name = name.Replace("-", " ").ToTitleCase();

            var star = _dbContext.Stars.FirstOrDefault(x => x.Title.ToLower() == name.ToLower().Trim());

            if (star != null) return star.Id;

            var newStar = await _htmlHelper.GetJavHiHiStar(name);

            if (newStar == null)
            {
                newStar = new Stars
                {
                    Title = name.Trim(),
                    TitleAscii = name.Trim(),
                    Slug = name.Trim().ToLower().Replace(" ", ""),
                    Seo = "{\"title\":\"" + name.Trim() + "\",\"keyword\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos " + name.Trim() + ", " + name.Trim() + " sexy\",\"description\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos " + name.Trim() + ", " + name.Trim() + " sexy\"}",
                    CreatedAt = DateTime.Now
                };
            }

            if (!string.IsNullOrWhiteSpace(newStar.ThumbName))
            {
                var uploadResults = await _ftpHelper.RemoteFiles(newStar.ThumbName, name.ToLower().Trim().Replace(" ", "-"));

                if (uploadResults.IsSuccessful)
                {
                    newStar.ThumbName = uploadResults.FileName;
                    newStar.FtpId = uploadResults.ServerId;
                }
            }

            await _dbContext.Stars.AddAsync(newStar);
            await _dbContext.SaveChangesAsync();

            return newStar.Id;
        }

        private async Task<int> GetTag(string name)
        {
            name = name.Replace("-", " ").ToTitleCase();

            var tag = _dbContext.Tags.FirstOrDefault(x => x.Title.ToLower() == name.ToLower().Trim());
            if (tag != null) return tag.Id;

            var newTag = new Tags
            {
                Title = name.Trim(),
                TitleAscii = name.Trim(),
                Slug = name.ToLower().Trim().Replace(" ","-"),
                Status = true,
                Seo = "{\"title\":\"" + name.Trim() + "\",\"keyword\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos " + name.Trim() + ", " + name.Trim() + " sexy\",\"description\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos " + name.Trim() + ", " + name.Trim() + " sexy\"}",
                CreatedAt = DateTime.Now
            };

            await _dbContext.AddAsync(newTag);

            await _dbContext.SaveChangesAsync();

            return newTag.Id;
        }

        private async Task<bool> SaveJavHiHiMovie(JavHiHiMovie movie)
        {
            if (_dbContext.Films.Any(x => x.Slug == movie.url)) return false;

            using (var dbTransaction = await _dbContext.Database.BeginTransactionAsync())
            {
                try
                {
                    var keyworks = string.Empty;

                    foreach (var star in movie.pornstars)
                    {
                        keyworks += star + ", ";
                    }

                    foreach (var tag in movie.tags)
                    {
                        keyworks += tag + ", ";
                    }

                    foreach (var cat in movie.categories)
                    {
                        keyworks += cat + ", ";
                    }

                    string thumb_cover = null;
                    string thumb_name = null;
                    int? ftpId = null;
                    if (!string.IsNullOrWhiteSpace(movie.image_small))
                    {
                        var ftpUpload = await _ftpHelper.RemoteFiles(movie.image_small, movie.url);
                        if (ftpUpload.IsSuccessful)
                        {
                            thumb_name = ftpUpload.FileName;
                            ftpId = ftpUpload.ServerId;
                        }
                    }

                    if (movie.image == movie.image_small)
                    {
                        thumb_cover = thumb_name;
                    }
                    else if (!string.IsNullOrWhiteSpace(movie.image))
                    {
                        var ftpUpload = await _ftpHelper.RemoteFiles(movie.image, movie.url + "-cover");
                        if (ftpUpload.IsSuccessful)
                        {
                            thumb_cover = ftpUpload.FileName;
                            ftpId = ftpUpload.ServerId;
                        }
                    }

                    if (string.IsNullOrWhiteSpace(thumb_cover))
                    {
                        thumb_cover = thumb_name;
                    }
                    if (string.IsNullOrWhiteSpace(thumb_name))
                    {
                        thumb_name = thumb_cover;
                    }

                    var newFilm = new Films
                    {
                        CategoryId = 1,
                        ThumbName = thumb_name,
                        CoverName = thumb_cover,
                        Title = movie.name,
                        TitleEn = movie.name,
                        TitleAscii = movie.name,
                        Slug = movie.url,
                        Date = movie.published,
                        Order = 1,
                        Online = 1,
                        Hot = 0,
                        Free = 0,
                        Slide = 0,
                        QualityId = 1,
                        Episodes = "HD",
                        ExistEpisodes = "HD",
                        Runtime = movie.duration,
                        Description = movie.name,
                        Storyline = movie.descriptions,
                        Viewed = int.Parse(movie.view.Replace(",","").Replace(".","")),
                        Seo = "{\"title\":\"" + movie.name + "\",\"desc\":\"" + (movie.descriptions.Length > 246 ? movie.descriptions.Substring(0, 240) + "..." : movie.descriptions) + "\",\"keyword\":\"" + keyworks.Trim().TrimEnd(',') + "\"}",
                        CreatedAt = DateTime.Now,
                        Reported = 0,
                        FtpId = ftpId
                    };


                    await _dbContext.Films.AddAsync(newFilm);

                    await _dbContext.SaveChangesAsync();

                    if (newFilm.Id > 0)
                    {
                        var idCountry = await GetCountry("Japanese");
                        await _dbContext.FilmCountries.AddAsync(new FilmCountries
                        {
                            CountryId = idCountry,
                            FilmId = newFilm.Id
                        });
                        await _dbContext.SaveChangesAsync();

                        var idDirector = GetDirector("JAV");
                        await _dbContext.FilmDirectors.AddAsync(new FilmDirectors
                        {
                            DirectorId = idDirector,
                            FilmId = newFilm.Id
                        });
                        await _dbContext.SaveChangesAsync();

                        var filmGenres = new List<FilmGenres>();
                        foreach (var genre in movie.categories)
                        {
                            var idGenre = await GetGenre(genre);
                            filmGenres.Add(new FilmGenres
                            {
                                FilmId = newFilm.Id,
                                GenreId = idGenre
                            });
                        }
                        await _dbContext.FilmGenres.AddRangeAsync(filmGenres);
                        await _dbContext.SaveChangesAsync();

                        var filmTags = new List<FilmTags>();
                        foreach (var tag in movie.tags)
                        {
                            var idTag = await GetTag(tag);
                            filmTags.Add(new FilmTags
                            {
                                FilmId = newFilm.Id,
                                TagId = idTag
                            });
                        }
                        await _dbContext.FilmTags.AddRangeAsync(filmTags);
                        await _dbContext.SaveChangesAsync();

                        var filmStars = new List<FilmStars>();
                        foreach (var star in movie.pornstars)
                        {
                            var idStar = await GetStar(star);
                            filmStars.Add(new FilmStars
                            {
                                FilmId = newFilm.Id,
                                StarId = idStar
                            });
                        }
                        await _dbContext.FilmStars.AddRangeAsync(filmStars);
                        await _dbContext.SaveChangesAsync();

                        var episodes = new List<Episodes>();
                        
                        foreach(var eps in movie.linkepisode)
                        {
                            episodes.Add(new Episodes
                            {
                                Title = "FULL",
                                FilmId = newFilm.Id,
                                FileName = eps,
                                Type = "Full",
                                Viewed = int.Parse(movie.view.Replace(",","").Replace(".","")),
                                CreatedAt = DateTime.Now
                            });
                        }

                        await _dbContext.Episodes.AddRangeAsync(episodes);
                        await _dbContext.SaveChangesAsync();
                    }

                    dbTransaction.Commit();
                    return true;
                }
                catch
                {
                    dbTransaction.Rollback();
                    return false;
                }
            }
        }
    }
}
