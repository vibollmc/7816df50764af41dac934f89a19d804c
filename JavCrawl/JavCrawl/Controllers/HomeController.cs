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
            //ViewBag.StartTime = DateTime.Now;
            //if (!string.IsNullOrWhiteSpace(link))
            //{
            //    try
            //    {
            //        model = await _htmlHelper.GetJavHiHiMovies(link);

            //        number = await _dbRepository.CrawlJavHiHiMovies(model);

            //    }
            //    catch(Exception ex)
            //    {
            //        err = ex.Message;
            //    }
            //}
            //ViewBag.EndTime = DateTime.Now;
            //ViewBag.Number = number;

            return View(model);
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
