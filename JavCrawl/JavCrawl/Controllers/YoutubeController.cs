using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Models;
using Microsoft.Extensions.Options;
using JavCrawl.Dal.Context;
using JavCrawl.Models.DbEntity;

namespace JavCrawl.Controllers
{
    public class YoutubeController : Controller
    {
        private readonly IYoutubeHelper _youtubeHelper;
        private readonly YoutubeSettings _youtubeSettings;
        private readonly IDbRepository _dbRepository;
        public YoutubeController(IYoutubeHelper youtubeHelper, IOptions<YoutubeSettings> youtubeSettings, IDbRepository dbRepository)
        {
            _youtubeHelper = youtubeHelper;
            _youtubeSettings = youtubeSettings.Value;
            _dbRepository = dbRepository;
        }
        public async Task<IActionResult> OAuth()
        {
            ViewBag.Message = await _youtubeHelper.Authorization();

            return View();
        }

        public async Task<IActionResult> Index(YoutubeModel model)
        {
            if (model == null) model = new YoutubeModel();

            if (string.IsNullOrWhiteSpace(model.CommentText)) model.CommentText = _youtubeSettings.CommentDefault;
            if (model.Max == null) model.Max = _youtubeSettings.MaxResultDefault;

            Stars star = new Stars();

            if (model.SelectedVideo != null && model.SelectedVideo.Count > 0)
            {
                await _youtubeHelper.Comment(model.SelectedVideo, model.CommentText);
            }

            if (string.IsNullOrWhiteSpace(model.Search))
            {
                star = await _dbRepository.GetStar();

                if (star != null)
                {
                    model.Search = star.Title + " jav star";
                    model.CommentText = string.Format(@"http://javmile.com/pornstar/{0}
{1} Video Sex, {1} JAV HD, Videos {1}, {1} sex

#{0} #javhd #jav #xxx #asianvideo #porn #asiansex #sex #18+﻿",star.Slug, star.Title);
                }
            }

            if (!string.IsNullOrWhiteSpace(model.Search) && model.Max != null && model.Max > 0)
            {

                model.Videos = await _youtubeHelper.Search(model.Search, model.Max.Value, model.Lat, model.Lon, model.Radius, model.PublishedAfter, model.PageToken);

                if (model.Videos != null && model.Videos.Count > 0)
                {
                    var videoid = model.Videos.Select(x => x.VideoId).ToList();

                    var commented = _dbRepository.GetYoutubeComment(videoid);

                    foreach(var video in model.Videos)
                    {
                        var c = commented.FirstOrDefault(x => x.VideoId == video.VideoId);
                        if (c!= null)
                        {
                            video.CommentedAt = c.CreatedAt;
                        }
                    }
                }
            }

            return View(model);
        }
    }
}