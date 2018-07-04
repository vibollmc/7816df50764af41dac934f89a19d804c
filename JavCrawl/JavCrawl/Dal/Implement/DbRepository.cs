using JavCrawl.Dal.Context;
using JavCrawl.Models;
using JavCrawl.Models.DbEntity;
using JavCrawl.Utility.Context;
using JavCrawl.Utility;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace JavCrawl.Dal.Implement
{
    public class DbRepository : IDbRepository
    {
        private readonly MySqlContext _dbContext;
        private readonly IFtpHelper _ftpHelper;
        private readonly IHtmlHelper _htmlHelper;

        private class Slide
        {
            public string url { get; set; }
            public string content { get; set; }
            public string img { get; set; }
        }

        public DbRepository(MySqlContext dbContext, IFtpHelper ftpHelper, IHtmlHelper htmlHelper)
        {
            _dbContext = dbContext;
            _ftpHelper = ftpHelper;
            _htmlHelper = htmlHelper;
        }

        public async Task<bool> JobUpdateSlideAndFilmMember()
        {
            try
            {
                var schedule = _dbContext.Settings.FirstOrDefault(x => x.Title == "genarate_slide_and_filmmember" && x.UpdatedAt < DateTime.Now);

                if (schedule == null) return false;

                var result = _dbContext.Films
                    .Where(x => x.ThumbName != x.CoverName && x.Online == 1 && x.DeletedAt == null)
                    .Select(x => x.Id)
                    .ToList();

                var idRandoms = new List<int>();
                var idFilms = new List<int>();

                for (var i = 1; i < 13; i++)
                {
                    var random = new Random();
                    var next = random.Next(0, result.Count - 1);

                    while (idRandoms.Contains(next))
                    {
                        next = random.Next(0, result.Count - 1);
                    }

                    idRandoms.Add(next);

                    idFilms.Add(result[next]);
                }

                await NewSlide(idFilms);

                await GenerateMemberVideo();

                schedule.UpdatedAt = DateTime.Now.AddDays(1);

                await _dbContext.SaveChangesAsync();

                return true;
            }
            catch
            {
                return false;
            }
        }

        public async Task<bool> AddGoogleApi(GoogleApi api)
        {
            var googleApi = _dbContext.GoogleApi.FirstOrDefault(x => x.ApiKey == api.ApiKey);

            if (googleApi != null)
            {
                googleApi.Name = api.Name;
                googleApi.FileName = api.FileName;
                googleApi.AuthorizedAt = null;
                googleApi.LastUsed = null;

                await _dbContext.SaveChangesAsync();

                return true;
            }

            _dbContext.GoogleApi.Add(api);

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public GoogleApi GetGoogleApi(int id)
        {
            return _dbContext.GoogleApi.FirstOrDefault(x => x.Id == id);
        }

        public GoogleApi GetGoogleApiToUse()
        {
            return _dbContext.GoogleApi.OrderBy(x => x.LastUsed).FirstOrDefault(x => x.AuthorizedAt != null && x.AuthorizedAt > DateTime.Now.AddDays(-1));
        }

        public IList<GoogleApi> GetGoogleApi()
        {
            return _dbContext.GoogleApi.ToList();
        }

        public async Task<bool> UpdateGoogleApiLastUsed(int? apiId)
        {
            var api = _dbContext.GoogleApi.FirstOrDefault(x => x.Id == apiId);

            if (api != null)
            {
                api.LastUsed = DateTime.Now;
                await _dbContext.SaveChangesAsync();
            }
            return true;
        }

        public async Task<bool> GoogleAuthorized(int apiId)
        {
            var api = _dbContext.GoogleApi.FirstOrDefault(x => x.Id == apiId);

            if (api != null)
            {
                api.AuthorizedAt = DateTime.Now;
                await _dbContext.SaveChangesAsync();
            }
            return true;
        }

        public async Task<bool> UpdateCrossImage()
        {
            var films = _dbContext.Films.Where(x => x.Id < 3868 && x.ThumbName != x.CoverName);

            foreach(var film in films)
            {
                var thumbTemp = film.ThumbName;
                film.ThumbName = film.CoverName;
                film.CoverName = thumbTemp;

                await _dbContext.SaveChangesAsync();
            }

            return true;
        }

        public async Task<Stars> GetStar()
        {
            var star = _dbContext.Stars.OrderByDescending(x => x.Id).FirstOrDefault(x => x.UpdatedAt == null);

            star.UpdatedAt = DateTime.Now;

            await _dbContext.SaveChangesAsync();

            return star;
        }

        public async Task<bool> GenerateMemberVideo()
        {
            var filmMember = _dbContext.Films.Where(x => x.Member != null);
            foreach(var film in filmMember)
            {
                film.Member = null;
            }

            await _dbContext.SaveChangesAsync();

            var min = _dbContext.Films.OrderBy(x => x.Id).FirstOrDefault().Id;
            var max = _dbContext.Films.OrderByDescending(x => x.Id).FirstOrDefault().Id;

            var idRandoms = new List<int>();
            var value = 0;
            for(var i = 0; i < 500; i++)
            {
                while(value == 0 || idRandoms.Contains(value))
                {
                    var random = new Random();
                    value = random.Next(min, max);
                }

                idRandoms.Add(value);
            }

            var newfilmMember = _dbContext.Films.Where(x => idRandoms.Contains(x.Id));

            foreach (var film in newfilmMember)
            {
                film.Member = 1;
            }

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public IList<YoutubeComment> GetYoutubeComment(IList<string> videoId)
        {
            return _dbContext.YoutubeComment.Where(x => videoId.Contains(x.VideoId)).ToList();
        }

        public async Task<bool> AddNewYoutubeComment(IList<YoutubeComment> youtube)
        {
            if (youtube != null)
            {
                await _dbContext.YoutubeComment.AddRangeAsync(youtube);

                await _dbContext.SaveChangesAsync();
            }

            return true;
        }

        public async Task<bool> NewSlide(IList<int> filmIds)
        {
            var films = _dbContext.Films
                    .Where(x => filmIds.Contains(x.Id))
                    .OrderByDescending(x => x.Date);

            var slides = new List<Slide>();

            foreach(var film in films )
            {
                var slide = new Slide();

                var ftp = _dbContext.Servers.FirstOrDefault(x => x.Id == film.FtpId);
                if (ftp == null) continue;
                
                var cat = _dbContext.Categories.FirstOrDefault(x => x.Id == film.CategoryId);
                if (cat == null) continue;

                slide.content = string.Format("<p>{0}</p>", film.Title);

                var ftpAccount = JsonConvert.DeserializeObject<FtpAccount>(ftp.Data);

                slide.img = string.Format("{0}/{1}{2}", ftpAccount.public_url, ftpAccount.dir, film.CoverName);

                slide.url = string.Format("{0}/{1}", cat.Slug, film.Slug);

                slides.Add(slide);
            }

            var setting = _dbContext.Settings.FirstOrDefault(x => x.Title == "Slide");

            if (setting == null)
            {
                setting = new Settings
                {
                    Title = "Slide",
                    Type = "slide",
                    Status = 1,
                    DataType = "json",
                    CreatedAt = DateTime.Now
                };

                _dbContext.Settings.Add(setting);
            }

            setting.UpdatedAt = DateTime.Now;
            setting.Data = JsonConvert.SerializeObject(slides);

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public IList<Films> GetFilmsToGenerateBigSlide()
        {
            var result = _dbContext.Films
                    .Where(x => x.ThumbName != x.CoverName && x.Online == 1 && x.DeletedAt == null)
                    .OrderByDescending(x => x.Date)
                    .Take(400)
                    .ToList();

            return result;
        }

        public IList<Episodes> GetEpisodesRemoted()
        {
            var episode = _dbContext.Episodes.Where(
                    x => (x.FileName.Contains("bitporno.com") && x.CustomerId == -1) ||
                        (x.FileName.Contains("openload.co") && 
                        x.FileName.Contains("javmile.com"))).OrderByDescending(x=>x.Id).Take(100);

            return episode.ToList();
        }

        public IList<Episodes> GetEpisodesRemoteError()
        {
            var episode = _dbContext.Episodes.Where(
                    x => 
                        (x.FileName.Contains("openload.co") || x.FileName.Contains("bitporno.com")) && 
                        x.CustomerId == 0).OrderByDescending(x => x.Id);

            return episode.ToList();
        }

        public IList<Episodes> GetEpisodesRemoting()
        {
            var episode = _dbContext.Episodes.Where(
                    x =>
                        (x.FileName.Contains("bitporno.com") && x.CustomerId != null && 
                        x.CustomerId != 0 && x.CustomerId != -1) ||
                        (x.FileName.Contains("openload.co") && 
                        !x.FileName.Contains("javmile.com") && 
                        x.CustomerId != null && x.CustomerId != 0)).OrderByDescending(x => x.Id);

            return episode.ToList();
        }

        public async Task<bool> UpdateEpisodeWithNewLink(int id, string link)
        {
            var eps = _dbContext.Episodes.FirstOrDefault(x => x.Id == id);
            if (eps == null) return true;

            eps.FileName = link;

            eps.CustomerId = -1;

            eps.UpdatedAt = DateTime.Now;

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public Episodes GetEpisodeToCheckStatusRemote(HostingLink hosting)
        {
            if (hosting == HostingLink.Openload)
                return _dbContext.Episodes
                    .OrderBy(x => x.Id)
                    .FirstOrDefault(
                        x => 
                            x.FileName.Contains("openload.co") && 
                            !x.FileName.Contains("javmile.com") && 
                            x.CustomerId != null && x.CustomerId != 0);
            else
                return _dbContext.Episodes
                    .OrderBy(x => x.Id)
                    .FirstOrDefault(
                        x =>
                            x.FileName.Contains("bitporno.com") &&
                            x.CustomerId != null && x.CustomerId != 0 && x.CustomerId != -1);
        }

        public async Task<bool> UpdateEpisodeRemoteId(int id, int remoteid)
        {
            var eps = _dbContext.Episodes.FirstOrDefault(x => x.Id == id);
            if (eps == null) return true;

            eps.CustomerId = remoteid;

            eps.UpdatedAt = DateTime.Now;

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public Episodes GetEpisodeToTranferOpenload(HostingLink hosting)
        {
            if (hosting == HostingLink.Openload)
                return _dbContext.Episodes
                    .OrderBy(x => x.Id)
                    .FirstOrDefault(
                        x =>
                            x.FileName.Contains("openload.co") &&
                            !x.FileName.Contains("javmile.com") &&
                            x.CustomerId == null);

            else
                return _dbContext.Episodes
                    .OrderBy(x => x.Id)
                    .FirstOrDefault(
                        x =>
                            x.FileName.Contains("bitporno.com") &&
                            x.FileName.Length < 64 &&
                            x.CustomerId == null);
        }

        public async Task<bool> UpdateImage()
        {
            try
            {
                //var jobs = _dbContext.JobListCrawl.Where(x => x.Id == 89 && x.Complete > 0);

                //foreach (var job in jobs)
                //{
                    var movies = await _htmlHelper.GetJavMovies("http://jav789.com/movie?q=yuka+minami+temptation+to+a+dirty+housewife&ajax=1");

                    if (movies == null || movies.movies == null || movies.movies.Count == 0) return true;

                    foreach (var movie in movies.movies)
                    {

                        if (!_dbContext.Films.Any(x => x.Slug == movie.url)) continue;

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

                        foreach (var star in movie.pornstars)
                        {
                            var newStar = await _htmlHelper.GetJavHiHiStar(star, movie.fromsite);

                            if (!string.IsNullOrWhiteSpace(newStar.ThumbName))
                            {
                                var uploadResults = await _ftpHelper.RemoteFiles(newStar.ThumbName, star.ToLower().Trim().Replace(" ", "-"));
                            }
                        }
                    }
                //}

                return true;

            }
            catch
            {
                return false;
            }
        }

        public async Task<bool> RunJobCrawl()
        {
            var jobs = _dbContext.JobListCrawl
                        .OrderBy(x => x.ScheduleAt)
                        .FirstOrDefault(x => (x.Status == 0 && x.ScheduleAt <= DateTime.Now) || (x.Always == 1 && x.ScheduleAt <= DateTime.Now));

            if (jobs == null) return true;

            try
            {

                jobs.StartAt = DateTime.Now;
                jobs.Status = 1;

                await _dbContext.SaveChangesAsync();

                var movies = await _htmlHelper.GetJavHiHiMovies(jobs.Link);

                if (movies == null || movies.movies == null || movies.movies.Count == 0)
                {
                    jobs.FinishAt = DateTime.Now;
                    jobs.Status = 2;

                    if (jobs.Always == 1)
                    {
                        jobs.ScheduleAt = DateTime.Now.AddDays(1);
                    }
            

                    await _dbContext.SaveChangesAsync();

                    return true;
                }

                var total = movies.movies.Count;

                var complete = await CrawlJavHiHiMovies(movies);

                jobs.FinishAt = DateTime.Now;
                jobs.Complete += complete;
                jobs.UnComplete += total - complete;
                jobs.Status = 2;

                if (jobs.Always == 1)
                {
                    jobs.ScheduleAt = DateTime.Now.AddDays(1);
                }

                await _dbContext.SaveChangesAsync();

            }
            catch(Exception ex)
            {
                jobs.Status = 3;
                jobs.Error = ex.Message;
                await _dbContext.SaveChangesAsync();
            }

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
            var results = _dbContext.JobListCrawl.OrderByDescending(x => x.ScheduleAt).Take(200);

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

        public async Task MixGenre()
        {
            var genres = _dbContext.Genres.Where(x => x.DeletedAt == null);

            foreach(var genre in genres)
            {
                var tempName = StandardGenre(genre.Title);

                if (tempName == genre.Title) continue;

                var joingenre = _dbContext.Genres.FirstOrDefault(x => x.Title == tempName && x.DeletedAt == null);

                if (joingenre == null)
                {
                    genre.Title = tempName;
                    genre.Slug = tempName.ToLower().Replace(" ", "-");

                    await _dbContext.SaveChangesAsync();

                    continue;
                }

                using(var dbTransaction = await _dbContext.Database.BeginTransactionAsync())
                {
                    try
                    {
                        genre.DeletedAt = DateTime.Now;
                        genre.Menu = 0;

                        await _dbContext.SaveChangesAsync();

                        var filmGenres = _dbContext.FilmGenres.Where(x => x.GenreId == genre.Id);

                        foreach (var filmGenre in filmGenres)
                        {
                            filmGenre.GenreId = joingenre.Id;

                            await _dbContext.SaveChangesAsync();
                        }

                        dbTransaction.Commit();
                    }
                    catch
                    {
                        dbTransaction.Rollback();
                    }
                }
                
            }
        }

        private string StandardGenre(string name)
        {
            if (name.StartsWith("Jav ")) name = name.Replace("Jav ", "");
            if (name.StartsWith("Asian ")) name = name.Replace("Asian ", "");
            if (name.StartsWith("Japanese ")) name = name.Replace("Japanese ", "");
            if (name == "Bigtits") name = "Big Tits";
            if (name == "Milf Housewife") name = "Milf";
            if (name == "Gangbang") name = "Gang Bang";
            if (name == "Outdoor Fucking") name = "Outdoor";
            if (name == "Squirt") name = "Squirting";
            if (name == "Young") name = "Teen";
            if (name == "Wife") name = "Wifehouse";
            if (name == "Handjob") name = "Hand Job";

            return name;
        }

        private async Task<int> GetGenre(string name)
        {
            name = name.Replace("-", " ").ToTitleCase();

            name = StandardGenre(name);
            
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

        private async Task<int> GetStar(string name, string fromSite)
        {
            name = name.Replace("-", " ").ToTitleCase();

            var star = _dbContext.Stars.FirstOrDefault(x => x.Title.ToLower() == name.ToLower().Trim());

            if (star != null) return star.Id;

            var newStar = await _htmlHelper.GetJavHiHiStar(name, fromSite);

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

        public async Task<bool> ReupdateSeoField()
        {
            var films = _dbContext.Films.Where(x => x.Id > 4047);

            foreach(var film in films)
            {
                var keyworks = string.Empty;

                var starFilm = _dbContext.FilmStars.Where(x => x.FilmId == film.Id).Select(x => x.StarId);

                foreach (var star in _dbContext.Stars.Where(x => starFilm.Contains(x.Id)).Select(x=>x.Title))
                {
                    keyworks += star + ", ";
                }

                var tagFilm = _dbContext.FilmTags.Where(x => x.FilmId == film.Id).Select(x => x.TagId);
                foreach(var tag in _dbContext.Tags.Where(x=>tagFilm.Contains(x.Id)).Select(x=>x.Title))
                {
                    keyworks += tag + ", ";
                }

                var catFilm = _dbContext.FilmGenres.Where(x => x.FilmId == film.Id).Select(x => x.GenreId);
                foreach(var cat in _dbContext.Genres.Where(x=> catFilm.Contains(x.Id)).Select(x=>x.Title))
                {
                    keyworks += cat + ", ";
                }

                var seo = new JavCrawl.Models.Seo.SeoFilm();
                seo.keyword = keyworks.Trim().TrimEnd(',');
                seo.title = film.Title;
                seo.desc = film.Storyline.Length > 246 ? film.Storyline.Substring(0, 240) + "..." : film.Storyline;

                film.Seo = JsonConvert.SerializeObject(seo);
                await _dbContext.SaveChangesAsync();
            }

            return true;
        }

        public async Task<bool> SaveJavHiHiMovie(JavHiHiMovie movie)
        {
            var film = _dbContext.Films.FirstOrDefault(x => x.Slug == movie.url);
            if (film != null)
            {
                return await UpdateEpisode(film.Id, movie.linkepisode);
            }

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
                    if (!string.IsNullOrWhiteSpace(movie.image))
                    {
                        var ftpUpload = await _ftpHelper.RemoteFiles(movie.image, movie.url);
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
                    else if (!string.IsNullOrWhiteSpace(movie.image_small))
                    {
                        var ftpUpload = await _ftpHelper.RemoteFiles(movie.image_small, movie.url + "-cover");
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

                    var seo = new JavCrawl.Models.Seo.SeoFilm();
                    seo.keyword = keyworks.Trim().TrimEnd(',');
                    seo.title = movie.name;
                    seo.desc = (movie.descriptions.Length > 246 ? movie.descriptions.Substring(0, 240) + "..." : movie.descriptions);

                    var newFilm = new Films
                    {
                        CategoryId = movie.fromsite == "hihi" ? 1 : 2,
                        ThumbName = thumb_name,
                        CoverName = thumb_cover,
                        Title = movie.name,
                        TitleEn = movie.name,
                        TitleAscii = movie.name,
                        Slug = movie.url,
                        Date = movie.published,
                        Order = movie.published.UnixTicks(),
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
                        Seo = JsonConvert.SerializeObject(seo),
                        CreatedAt = DateTime.Now,
                        Reported = 0,
                        FtpId = ftpId,
                        Extend = movie.fromsite
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
                            var idStar = await GetStar(star, movie.fromsite);
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
                                Title = eps.Contains("bitporno") ? "Server BP" : (eps.Contains("openload") ? "Server OL" : "Server HD"),
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
                catch(Exception ex)
                {
                    Console.WriteLine("SaveJavHiHiMovie: " + ex.Message);

                    dbTransaction.Rollback();
                    return false;
                }
            }
        }

        private async Task<bool> UpdateEpisode(int? filmId, IList<string> epsLinks)
        {
            try
            {
                var episodes = new List<Episodes>();

                foreach (var eps in epsLinks)
                {

                    if (eps.Contains("onecloud.media")) continue;

                    if (eps.Contains("javhihi.com")) {
                        if (_dbContext.Episodes.Any(x => x.FilmId == filmId &&
                            x.FileName.Contains("javhihi.com"))) continue;
                    }

                    if (eps.Contains("javbuz.com"))
                    {
                        if (_dbContext.Episodes.Any(x => x.FilmId == filmId &&
                            x.FileName.Contains("javbuz.com"))) continue;
                    }

                    if (eps.Contains("jav789.com"))
                    {
                        if (_dbContext.Episodes.Any(x => x.FilmId == filmId &&
                            x.FileName.Contains("jav789.com"))) continue;
                    }

                    if (eps.Contains("openload"))
                    {
                        if (_dbContext.Episodes.Any(x => x.FilmId == filmId &&
                            x.FileName.Contains("openload") && x.FileName.Contains("javmile.com"))) continue;
                    }


                    var exsits = _dbContext.Episodes.Any(
                        x => x.FilmId == filmId && x.FileName == eps);

                    if (!exsits)
                    {
                        episodes.Add(new Episodes
                        {
                            Title = eps.Contains("bitporno") ? "Server BP" : (eps.Contains("openload") ? "Server OL" : "Server HD"),
                            FilmId = filmId,
                            FileName = eps,
                            Type = "Full",
                            Viewed = 0,
                            CreatedAt = DateTime.Now
                        });
                    }
                }

                if (episodes.Count > 0)
                {
                    await _dbContext.Episodes.AddRangeAsync(episodes);
                    await _dbContext.SaveChangesAsync();
                }

                return true;
            }
            catch(Exception ex)
            {
                Console.WriteLine("UpdateEpisode: " + ex.Message);

                return false;
            }
        }
    }
}
