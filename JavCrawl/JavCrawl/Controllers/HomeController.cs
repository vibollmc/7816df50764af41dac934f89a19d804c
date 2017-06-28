using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Dal.Context;
using JavCrawl.Models;

namespace JavCrawl.Controllers
{
    public class HomeController : Controller
    {
        private readonly IHtmlHelper _htmlHelper;
        private readonly IDbRepository _dbRepository;
        public HomeController(IHtmlHelper htmlHelper, IDbRepository dbRepository)
        {
            _htmlHelper = htmlHelper;
            _dbRepository = dbRepository;
        }
        public IActionResult Index(string message)
        {
            var model = _dbRepository.GetSchedule();
            if (!string.IsNullOrWhiteSpace(message))
            {
                ModelState.AddModelError("Error", message);
            }

            ViewBag.Message = message;

            return View(model);
        }

        public async Task<IActionResult> Test()
        {
            var results = true;
            //var movies = await _htmlHelper.GetJavHiHiMovies(link);
            //var results = await _dbRepository.CrawlJavHiHiMovies(movies);

            //for (var i = 72; i > 0; i--)
            //{

            //    var newSchedule = new JavCrawl.Models.DbEntity.JobListCrawl
            //    {
            //        Link = string.Format("http://javhihi.com/movie?sort=published&page={0}&ajax=1", i),
            //        ScheduleAt = DateTime.Now.AddMinutes((89-i) * 10),
            //        Always = false
            //    };

            //    results = await _dbRepository.SaveSchedule(newSchedule);
            //}

            //await _dbRepository.UpdateImage();

            var movies = await _htmlHelper.GetJavHiHiMovies("http://javhihi.com/movie?sort=published&page=1&ajax=1");

            var complete = await _dbRepository.CrawlJavHiHiMovies(movies);

            return Json(new { OK = results });
        }

        public async Task<IActionResult> SetSchedule(string link, DateTime? schedule, bool always)
        {
            if (string.IsNullOrWhiteSpace(link)) return RedirectToAction("Index", new { message = "[Link] cannot be null." });
            if (!schedule.HasValue) return RedirectToAction("Index", new { message = "[Schedule] cannot be null." });

            try
            {
                var newSchedule = new JavCrawl.Models.DbEntity.JobListCrawl
                {
                    Link = link,
                    ScheduleAt = schedule,
                    Always = always
                };

                var results = await _dbRepository.SaveSchedule(newSchedule);

                return RedirectToAction("Index", new { message = "Save successful." });

            }
            catch(Exception ex)
            {
                return RedirectToAction("Index", new { message = ex.Message });
            }
        }

        public IActionResult About()
        {
            ViewData["Message"] = "Your application description page.";

            return View();
        }

        public IActionResult Contact()
        {
            ViewData["Message"] = "Your contact page.";

            return View();
        }

        public IActionResult Error()
        {
            return View();
        }
    }
}
