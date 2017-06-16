using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Models;

namespace JavCrawl.Controllers
{
    public class VideoController : Controller
    {
        public IActionResult Index(string link)
        {
            var model = new List<VideoApi>();

            return Json(model);
        }
    }
}