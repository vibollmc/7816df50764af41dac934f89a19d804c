using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Models;

namespace JavCrawl.Controllers
{
    public class YoutubeController : Controller
    {
        private readonly IYoutubeHelper _youtubeHelper;
        public YoutubeController(IYoutubeHelper youtubeHelper)
        {
            _youtubeHelper = youtubeHelper;
        }
        public async Task<IActionResult> Index(string keyword, int? max, IList<string> video)
        {

            if (video != null && video.Count > 0)
            {
                var commentText = @"http://javmile.com/dvd
FREE JAVHD, DOWNLOAD JAV, Video Asian Sexy, JAV Streaming

#javhd #jav #xxx #asianvideo #porn #asiansex #sex #18+﻿";
                foreach(var e in video)
                {
                    await _youtubeHelper.Comment(e, commentText);
                }
            }

            IList<YoutubeVideo> model = null;

            ViewBag.Keyword = keyword;
            ViewBag.max = (max == null || max <= 0) ? 50 : max.Value;

            if (!string.IsNullOrWhiteSpace(keyword) && max != null && max > 0)
            {
                model = await _youtubeHelper.Search(keyword, max.Value);
            }

            return View(model);
        }
    }
}